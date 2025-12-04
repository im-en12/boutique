<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;

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
Route::get('/shop/{slug}', [ArticleController::class, 'show'])->name('article.show');


// Cart routes
Route::middleware('auth')->group(function () {
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
});
// Public cart routes (no auth required)
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::get('/cart/check/{slug}', [CartController::class, 'isInCart'])->name('cart.check');

// Breeze authentication routes
require __DIR__.'/auth.php';
