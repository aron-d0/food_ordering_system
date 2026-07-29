<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return auth()->user()->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('customer.menu');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::resource('foods', FoodController::class);
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
});

Route::middleware(['auth', 'customer'])->name('customer.')->group(function () {
    Route::get('/menu', [CustomerController::class, 'menu'])->name('menu');
    Route::get('/foods/{food}', [CustomerController::class, 'show'])->name('foods.show');
    Route::post('/cart', [CustomerController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CustomerController::class, 'cart'])->name('cart.index');
    Route::patch('/cart/{food}', [CustomerController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/{food}', [CustomerController::class, 'removeFromCart'])->name('cart.remove');
    Route::delete('/cart', [CustomerController::class, 'clearCart'])->name('cart.clear');
    Route::get('/checkout', [CustomerController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [CustomerController::class, 'placeOrder'])->name('checkout.place');
    Route::get('/order-success', [CustomerController::class, 'success'])->name('order.success');
    Route::get('/my-orders', [CustomerController::class, 'orders'])->name('orders.index');
});
