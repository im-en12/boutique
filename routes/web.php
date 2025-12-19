<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop routes
Route::get('/shop', [ArticleController::class, 'shop'])->name('shop');
Route::get('/product/{slug}', [ArticleController::class, 'show'])->name('product.show');


// Cart routes
Route::middleware('auth')->group(function () {
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
});
   // Checkout routes (using OrderController)
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/place-order', [OrderController::class, 'placeOrder'])->name('checkout.placeOrder');
    Route::get('/order/confirmation/{order}', [OrderController::class, 'confirmation'])->name('order.confirmation');

// Public cart routes (no auth required)
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::get('/cart/check/{slug}', [CartController::class, 'isInCart'])->name('cart.check');
// Dashboard routes

// Dashboard routes - ONLY ONE ROUTE FOR /dashboard
Route::middleware('auth')->group(function () {
    // Single dashboard route using the controller
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Dashboard actions
    Route::post('/dashboard/order/{id}/cancel', [DashboardController::class, 'cancelOrder'])->name('dashboard.order.cancel');
    Route::post('/dashboard/favorite/{articleId}/toggle', [DashboardController::class, 'toggleFavorite'])->name('dashboard.favorite.toggle');
    Route::post('/dashboard/review/{articleId}/submit', [DashboardController::class, 'submitReview'])->name('dashboard.review.submit');
});
// Breeze authentication routes
require __DIR__.'/auth.php';

// ========== ADMIN ROUTES ==========
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', function() {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Redirect /admin to /admin/dashboard
    Route::get('/', function() {
        return redirect()->route('admin.dashboard');
    });
    
    // Orders
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'adminShow'])->name('orders.show');
    Route::post('/orders/{order}/update-status', [OrderController::class, 'adminUpdateStatus'])->name('orders.update-status');
    
    // Articles (Products)
    Route::get('/articles', [ArticleController::class, 'adminIndex'])->name('articles.index');
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
        Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
});
