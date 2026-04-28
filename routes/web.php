<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductWebController;
use App\Http\Controllers\OrderWebController;
use App\Http\Controllers\AuthWebController;
use App\Http\Controllers\ApiDocsController;
use Illuminate\Support\Facades\Route;

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// API Documentation
Route::get('/api-docs', [ApiDocsController::class, 'index'])->name('api.docs');
Route::get('/openapi.json', [ApiDocsController::class, 'spec'])->name('api.spec');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthWebController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthWebController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthWebController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthWebController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout')->middleware('auth');

// Product pages
Route::get('/products', [ProductWebController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductWebController::class, 'show'])->name('products.show');

// Cart routes
Route::middleware(['web'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
});

// Order routes (require authentication)
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/orders', [OrderWebController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderWebController::class, 'show'])->name('orders.show');
    Route::get('/checkout', [OrderWebController::class, 'checkout'])->name('checkout');
    Route::post('/orders/checkout', [OrderWebController::class, 'processCheckout'])->name('orders.processCheckout');
});

// Admin routes (require authentication and admin role)
Route::middleware(['web', 'auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    
    // Product management
    Route::get('/products', [\App\Http\Controllers\AdminController::class, 'products'])->name('products.index');
    Route::get('/products/create', [\App\Http\Controllers\AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [\App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [\App\Http\Controllers\AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{id}', [\App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [\App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
    
    // Order management
    Route::get('/orders', [\App\Http\Controllers\AdminController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\AdminController::class, 'showOrder'])->name('orders.show');
    Route::put('/orders/{id}/status', [\App\Http\Controllers\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
    // Category management
    Route::get('/categories', [\App\Http\Controllers\AdminController::class, 'categories'])->name('categories.index');
    Route::post('/categories', [\App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [\App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [\App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');
    
    // Revenue reports
    Route::get('/revenue', [\App\Http\Controllers\AdminController::class, 'revenue'])->name('revenue');
});
