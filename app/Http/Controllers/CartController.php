<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * View cart page
     */
    public function view()
    {
        // Manual auth check
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view your cart');
        }
        
        $cartItems = Cart::with(['article' => function($query) {
            $query->with(['category', 'brand'])
                  ->select('id', 'name', 'slug', 'price', 'image', 'stock', 'excerpt');
        }])
        ->where('user_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->get();
        
        // Calculate totals
        $subtotal = $cartItems->sum(function($item) {
            return ($item->article->price ?? 0) * $item->quantity;
        });
        
        $tax = $subtotal * 0.10;
        $total = $subtotal + $tax;
        
        return view('cart', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'cartCount' => $cartItems->count()
        ]);
    }
    
    /**
     * Add item to cart via AJAX
     */
    public function add(Request $request)
    {
        // Manual auth check
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to add items to cart',
                'redirect' => route('login')
            ], 401);
        }
        
        $request->validate([
            'product_slug' => 'required|string',
            'quantity' => 'integer|min:1|max:20'
        ]);
        
        try {
            $article = Article::where('slug', $request->product_slug)->first();
            
            if (!$article) {
                return response()->json(['success' => false, 'message' => 'Product not found'], 404);
            }
            
            // Check stock
            $requestedQuantity = $request->quantity ?? 1;
            if ($article->stock < $requestedQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$article->stock} items available"
                ], 400);
            }
            
            // Check if product is already in cart
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('article_id', $article->id)
                ->first();
            
            if ($cartItem) {
                // Calculate new total quantity
                $newQuantity = $cartItem->quantity + $requestedQuantity;
                
                // Check if new quantity exceeds stock
                if ($newQuantity > $article->stock) {
                    $availableQuantity = $article->stock - $cartItem->quantity;
                    return response()->json([
                        'success' => false,
                        'message' => "Only {$availableQuantity} more items available"
                    ], 400);
                }
                
                // Update quantity
                $cartItem->quantity = $newQuantity;
                $cartItem->save();
                
                $action = 'updated';
            } else {
                // Create new cart item
                $cartItem = Cart::create([
                    'user_id' => Auth::id(),
                    'article_id' => $article->id,
                    'quantity' => $requestedQuantity
                ]);
                
                $action = 'added';
            }
            
            // Get updated cart count
            $cartCount = Cart::where('user_id', Auth::id())->count();
            
            return response()->json([
                'success' => true,
                'message' => "Product {$action} to cart successfully",
                'cart_count' => $cartCount
            ]);
            
        } catch (\Exception $e) {
            Log::error('Add to cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        
        try {
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('id', $id)
                ->with('article')
                ->first();
            
            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found in cart'
                ], 404);
            }
            
            $articleName = $cartItem->article->name;
            $cartItem->delete();
            
            // Get updated cart count
            $cartCount = Cart::where('user_id', Auth::id())->count();
            
            return response()->json([
                'success' => true,
                'message' => "{$articleName} removed from cart",
                'cart_count' => $cartCount
            ]);
            
        } catch (\Exception $e) {
            Log::error('Remove from cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item from cart'
            ], 500);
        }
    }
    
    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        
        $request->validate([
            'quantity' => 'required|integer|min:1|max:50'
        ]);
        
        try {
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('id', $id)
                ->with('article')
                ->first();
            
            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found in cart'
                ], 404);
            }
            
            // Check stock availability
            if ($request->quantity > $cartItem->article->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$cartItem->article->stock} items available in stock"
                ], 400);
            }
            
            // Update quantity
            $cartItem->quantity = $request->quantity;
            $cartItem->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Quantity updated successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Update cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart item'
            ], 500);
        }
    }
    
    /**
     * Clear entire cart
     */
    public function clear()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        
        try {
            $count = Cart::where('user_id', Auth::id())->count();
            
            if ($count === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is already empty'
                ], 400);
            }
            
            Cart::where('user_id', Auth::id())->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully',
                'cart_count' => 0
            ]);
            
        } catch (\Exception $e) {
            Log::error('Clear cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart'
            ], 500);
        }
    }
    
    /**
     * Get cart count for AJAX requests
     */
    public function count()
    {
        if (!Auth::check()) {
            return response()->json([
                'count' => 0,
                'is_authenticated' => false
            ]);
        }
        
        $count = Cart::where('user_id', Auth::id())->count();
        return response()->json([
            'count' => $count,
            'is_authenticated' => true
        ]);
    }
    
    /**
     * Check if product is in cart (for UI indication)
     */
    public function isInCart($slug)
    {
        if (!Auth::check()) {
            return response()->json(['in_cart' => false], 200);
        }
        
        try {
            $article = Article::where('slug', $slug)->first();
            
            if (!$article) {
                return response()->json(['in_cart' => false], 200);
            }
            
            $inCart = Cart::where('user_id', Auth::id())
                ->where('article_id', $article->id)
                ->exists();
            
            $cartItem = null;
            if ($inCart) {
                $cartItem = Cart::where('user_id', Auth::id())
                    ->where('article_id', $article->id)
                    ->first();
            }
            
            return response()->json([
                'in_cart' => $inCart,
                'quantity' => $inCart ? $cartItem->quantity : 0,
                'item_id' => $inCart ? $cartItem->id : null
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['in_cart' => false], 200);
        }
    }
 

}