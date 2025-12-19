<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;
use App\Models\Cart;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();
           $user = Auth::user();
        
        // ========== CHECK IF USER IS ADMIN ==========
        if ($user->role === 'admin') {
            // Admin users should only see admin dashboard
            // Skip all cart merging logic for admins
            return redirect()->route('admin.dashboard');
        }
        
        // Get session cart before clearing
        $sessionCart = session()->get('guest_cart', []);
        
        // Check if user was trying to add to cart before login
        if ($request->has('add_to_cart')) {
            $productSlug = $request->input('add_to_cart');
            
            // Find the article
            $article = Article::where('slug', $productSlug)->first();
            
            if ($article) {
                // Add to cart
                $cartItem = Cart::firstOrCreate([
                    'user_id' => Auth::id(),
                    'article_id' => $article->id
                ], [
                    'quantity' => 1
                ]);
                
                if (!$cartItem->wasRecentlyCreated) {
                    $cartItem->increment('quantity');
                }
                
                // Clear session cart
                session()->forget('guest_cart');
                
                // Redirect to cart with success message
                return redirect()->route('cart.view')->with('success', 'Product added to cart!');
            }
        }
        
        // Merge session cart with user cart
        if (!empty($sessionCart)) {
            foreach ($sessionCart as $slug => $quantity) {
                $article = Article::where('slug', $slug)->first();
                
                if ($article) {
                    $cartItem = Cart::firstOrCreate([
                        'user_id' => Auth::id(),
                        'article_id' => $article->id
                    ], [
                        'quantity' => $quantity
                    ]);
                    
                    if (!$cartItem->wasRecentlyCreated) {
                        $cartItem->quantity += $quantity;
                        $cartItem->save();
                    }
                }
            }
            
            // Clear session cart
            session()->forget('guest_cart');
            
            // Redirect to cart
            return redirect()->route('cart.view')->with('success', 'Your cart items have been saved!');
        }
        
        // Handle normal redirect
        if ($request->has('redirect')) {
            return redirect($request->input('redirect'));
        }
        
        return redirect()->intended(route('shop'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}