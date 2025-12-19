<?php
namespace App\Http\Controllers;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Article;

class OrderController extends Controller
{
    /**
     * Show checkout page
     */
    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to checkout');
        }
        
        $cartItems = Cart::with(['article' => function($query) {
            $query->with(['category', 'brand'])
                  ->select('id', 'name', 'slug', 'price', 'image', 'stock');
        }])
        ->where('user_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->get();
        
        // Check if cart is empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.view')->with('error', 'Your cart is empty');
        }
        
        // Calculate totals
        $subtotal = $cartItems->sum(function($item) {
            return ($item->article->price ?? 0) * $item->quantity;
        });
        
        $tax = $subtotal * 0.10;
        $total = $subtotal + $tax;
        
        return view('checkout', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total
        ]);
    }
    
    /**
     * Process order placement
     */
    public function placeOrder(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to place order'
            ], 401);
        }
        
        try {
            DB::beginTransaction();
            
            $userId = Auth::id();
            $cartItems = Cart::with('article')->where('user_id', $userId)->get();
            
            // Check if cart is empty
            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty'
                ], 400);
            }
            
            // Check stock for all items
            foreach ($cartItems as $item) {
                if ($item->article->stock < $item->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for {$item->article->name}. Only {$item->article->stock} available."
                    ], 400);
                }
            }
            
            // Calculate total
            $subtotal = $cartItems->sum(function($item) {
                return ($item->article->price ?? 0) * $item->quantity;
            });
            $tax = $subtotal * 0.10;
            $total = $subtotal + $tax;
            
            // Create order (simple version)
            $order = Order::create([
                'user_id' => $userId,
                'total_price' => $total,
                'status' => Order::STATUS_PENDING,
            ]);
            
            // Create order items and update stock
            foreach ($cartItems as $cartItem) {
                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'article_id' => $cartItem->article_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->article->price,
                ]);
                
                // Update article stock and sales count
                $article = $cartItem->article;
                $article->stock -= $cartItem->quantity;
                $article->sales_count += $cartItem->quantity;
                $article->save();
            }
            
            // Clear the cart
            Cart::where('user_id', $userId)->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $order->id,
                'redirect' => route('order.confirmation', $order->id)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order placement error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Show order confirmation page
     */
    public function confirmation($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $order = Order::with(['items.article', 'user'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        //add cancellation option
        $canCancel = $order->canBeCancelled();
        return view('order-confirmation', compact('order', 'canCancel'));
    }   
    /**
     * ADMIN: List all orders
     */
    public function adminIndex(Request $request)
    {
        // Check if user is admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $status = $request->get('status');
        
        $orders = Order::with('user')
            ->when($status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(20);
            
        return view('admin.orders.index', compact('orders'));
    }
    
    /**
     * ADMIN: Show order details
     */
    public function adminShow(Order $order)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $order->load(['user', 'items.article']);
        
        return view('admin.orders.show', compact('order'));
    }
    
    /**
     * ADMIN: Update order status
     */
    public function adminUpdateStatus(Request $request, Order $order)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'status' => 'required|in:pending,paid,shipped,delivered,cancelled',
        ]);
        
        $oldStatus = $order->status;
        $newStatus = $request->status;
        
        // Update order status
        $order->status = $newStatus;
        $order->save();
        
        // If changing to delivered
        if ($oldStatus !== Order::STATUS_DELIVERED && $newStatus === Order::STATUS_DELIVERED) {
            // Order is now delivered - customer can review
        }
        
        // If cancelling, restore stock
        if ($newStatus === Order::STATUS_CANCELLED && $oldStatus !== Order::STATUS_CANCELLED) {
            foreach ($order->items as $item) {
                $article = $item->article;
                $article->stock += $item->quantity;
                $article->sales_count -= $item->quantity;
                $article->save();
            }
        }
        
        return redirect()->route('admin.orders.index')
            ->with('success', "Order #{$order->id} status updated to " . ucfirst($newStatus));
    }
}