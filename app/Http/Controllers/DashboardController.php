<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;


use App\Models\Order;
use App\Models\Favorite;
use App\Models\Review;
use App\Models\Article;

class DashboardController extends Controller
{
    /**
     * Main dashboard - handles everything
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // What section to show? Default to 'dashboard'
        $section = $request->query('section', 'dashboard');
        
        // Common data for all sections
        $data = [
            'section' => $section,
            'stats' => $this->getUserStats($user),
        ];
        
        // Load data based on section
        switch ($section) {
            case 'orders':
                $data['orders'] = Order::with(['items.article'])
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                break;
                
            case 'favorites':
                $data['favorites'] = Favorite::with('article')
                    ->where('user_id', $user->id)
                    ->paginate(12);
                break;
                
            case 'reviews':
                $data['reviews'] = Review::with('article')
                    ->where('user_id', $user->id)
                    ->paginate(10);
                break;
                
            default: // 'dashboard' - show summary
                $data['orders'] = Order::with(['items.article'])
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->take(5)->get();
                    
                $data['favorites'] = Favorite::with('article')
                    ->where('user_id', $user->id)
                    ->latest()->take(6)->get();
                    
                $data['reviews'] = Review::with('article')
                    ->where('user_id', $user->id)
                    ->latest()->take(3)->get();
                    
                $data['reviewableArticles'] = Article::whereHas('orderItems.order', function($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->where('status', '!=', Order::STATUS_CANCELLED);
                })
                ->whereDoesntHave('reviews', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->limit(3)->get();
                break;
        }
        
        return view('dashboard', $data);
    }
    
    /**
     * Cancel an order
     */
    public function cancelOrder(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        
        // Check if can be cancelled (within 24 hours & pending)
        $hoursSinceOrder = now()->diffInHours($order->created_at);
        $canCancel = $order->status === Order::STATUS_PENDING && 
                    $hoursSinceOrder <= 24 && 
                    $order->status !== Order::STATUS_CANCELLED;
        
        if (!$canCancel) {
            return back()->with('error', 'Order cannot be cancelled. Only pending orders within 24 hours can be cancelled.');
        }
        
        // Update status
        $order->update(['status' => Order::STATUS_CANCELLED]);
        
        // Restore stock
        foreach ($order->items as $item) {
            $article = $item->article;
            $article->stock += $item->quantity;
            $article->sales_count -= $item->quantity;
            $article->save();
        }
        
        return back()->with('success', 'Order cancelled successfully.');
    }
    
    /**
     * Toggle favorite (add/remove)
     */
    public function toggleFavorite(Request $request, $articleId)
    {
        $user = Auth::user();
        
        $existing = Favorite::where('user_id', $user->id)
                           ->where('article_id', $articleId)
                           ->first();
        
        if ($existing) {
            $existing->delete();
            $message = 'Removed from favorites';
            $action = 'removed';
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'article_id' => $articleId,
            ]);
            $message = 'Added to favorites!';
            $action = 'added';
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'action' => $action,
            ]);
        }
        
        return back()->with('success', $message);
    }
    
    /**
     * Submit a review
     */
  // In DashboardController.php - REPLACE the submitReview method with this:

public function submitReview(Request $request, $articleId)
{
    // Simple validation
    $request->validate([
        'rating' => 'required|integer|between:1,5',
        'comment' => 'nullable|string|max:1000',
        'order_id' => 'required|exists:orders,id',
    ]);
    
    $user = Auth::user();
    
    // 1. Find the order
    $order = Order::where('id', $request->order_id)
                  ->where('user_id', $user->id)
                  ->first();
    
    if (!$order) {
        return back()->with('error', 'Order not found.');
    }
    
    // 2. CHECK IF ORDER IS DELIVERED (MOST IMPORTANT!)
    if ($order->status !== 'delivered') {  // ← Change 'delivered' to your actual status
        return back()->with('error', 'You can only review products that have been delivered.');
    }
    
    // 3. Check if product is in the order
    $hasPurchased = $order->items()->where('article_id', $articleId)->exists();
    
    if (!$hasPurchased) {
        return back()->with('error', 'This product is not in your order.');
    }
    
    // 4. Check if already reviewed
    $alreadyReviewed = Review::where('user_id', $user->id)
        ->where('article_id', $articleId)
        ->where('order_id', $request->order_id)
        ->exists();
        
    if ($alreadyReviewed) {
        return back()->with('error', 'You already reviewed this product.');
    }
    
    // 5. Create review
    Review::create([
        'user_id' => $user->id,
        'article_id' => $articleId,
        'order_id' => $request->order_id,
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);
    
    return back()->with('success', 'Thank you for your review!');
}
    
    /**
     * Get user statistics
     */
    private function getUserStats($user)
    {
        return [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'pending_orders' => Order::where('user_id', $user->id)
                ->where('status', Order::STATUS_PENDING)->count(),
            'total_favorites' => Favorite::where('user_id', $user->id)->count(),
            'total_reviews' => Review::where('user_id', $user->id)->count(),
        ];
    }
}