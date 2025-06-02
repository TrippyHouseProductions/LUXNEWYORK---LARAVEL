<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Route::get('/', function () {
//     return view('welcome');
// });

// NOTE guest pages routes
Route::view('/', 'welcome');
Route::view('/products', 'products.index');
Route::view('products/{id}', 'products.show');
Route::view('/categories', 'categories.index');
Route::view('/categories/{id}', 'categories.show');
Route::get('/product-search', function () {
    return view('product-search-page');
});
// Route::view('/login', 'auth.login');
// Route::view('/register', 'auth.register');

// NOTE admin pages routes
Route::view('/admin/login', 'admin.login');

// NOTE Handle admin login form (POST)
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login');


Route::middleware([
    'auth:sanctum', config('jetstream.auth_session'), 'verified',
])->group(function () {

    // NOTE customer pages routes
    Route::view('/cart', 'cart');
    Route::view('/wishlist', 'wishlist');
    Route::view('/checkout', 'checkout');
    Route::view('/orders', 'orders');

    // NOTE there's no show function in orders
    // Route::view('/orders/{id}', 'orders.show');
    Route::view('/profile', 'profile');         // Optional
    Route::view('/dashboard', 'dashboard');     // Optional
    Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');

});


Route::middleware([
    'auth:sanctum', config('jetstream.auth_session'), 'verified', 'is_admin'
])->group(function () {
    // NOTE admin routes
    Route::view('/admin/dashboard', 'admin.dashboard');
    Route::view('/admin/products', 'admin.products.index');
    Route::view('/admin/categories', 'admin.categories.index');
    Route::view('/admin/orders', 'admin.orders.index');
    Route::view('/admin/users', 'admin.users.index');
    Route::view('/admin/blog', 'admin.blog.index');
});

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'is_admin'])
    ->name('admin.logout');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
