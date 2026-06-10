<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SSLCommerzController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\AamarPayController;

// Public routes
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Payment result pages (outside auth — SSLCommerz redirects here)
Route::get('/payment/success/{orderNumber}', function($orderNumber) {
    return view('payment.success', ['orderNumber' => $orderNumber]);
})->name('payment.success');
Route::get('/payment/fail', function() {
    return view('payment.fail');
})->name('payment.fail');
Route::get('/payment/cancel', function() {
    return view('payment.cancel');
})->name('payment.cancel');

// SSLCommerz callbacks (POST, no auth)
Route::post('/sslcommerz/success', [SSLCommerzController::class, 'success'])->name('sslcommerz.success');
Route::post('/sslcommerz/fail',    [SSLCommerzController::class, 'fail'])->name('sslcommerz.fail');
Route::post('/sslcommerz/cancel',  [SSLCommerzController::class, 'cancel'])->name('sslcommerz.cancel');


// aamarPay callbacks
Route::post('/aamarpay/success', [AamarPayController::class, 'success'])->name('aamarpay.success');
Route::post('/aamarpay/fail',    [AamarPayController::class, 'fail'])->name('aamarpay.fail');
Route::post('/aamarpay/cancel',  [AamarPayController::class, 'cancel'])->name('aamarpay.cancel');

// Authenticated customer routes
Route::middleware(['auth'])->group(function () {
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place-order');

    // Payment initiate
    Route::get('/payment/initiate', [PaymentController::class, 'initiate'])->name('payment.initiate');

    // Order history
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Products CRUD
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);

    // Orders
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');

    // Users
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.update-role');

    // Payments
    Route::get('/payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
});

require __DIR__.'/auth.php';
