<?php
// // NOTE was there already
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;

// // NOTE added by me
// use App\Http\Controllers\Api\ProductController;
// use App\Http\Controllers\Api\CategoryController;
// use App\Http\Controllers\Api\CartController;
// use App\Http\Controllers\Api\WishlistController;
// use App\Http\Controllers\Api\OrderController;
// use App\Http\Controllers\Api\AuthController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::post('/login', [AuthController::class, 'login']);

// // Public routes
// Route::get('products', [ProductController::class, 'index']);
// Route::get('products/{id}', [ProductController::class, 'show']);
// Route::get('categories', [CategoryController::class, 'index']);
// Route::get('categories/{id}', [CategoryController::class, 'show']);

// // Authenticated customer routes
// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('cart', [CartController::class, 'index']);
//     Route::post('cart', [CartController::class, 'store']);
//     Route::delete('cart/{itemId}', [CartController::class, 'destroy']);
//     Route::delete('cart', [CartController::class, 'clear']);
    
//     Route::get('wishlist', [WishlistController::class, 'index']);
//     Route::post('wishlist', [WishlistController::class, 'store']);
//     Route::delete('wishlist/{itemId}', [WishlistController::class, 'destroy']);

//     Route::get('orders', [OrderController::class, 'index']);
//     Route::post('orders', [OrderController::class, 'store']);

//     Route::post('/logout', [AuthController::class, 'logout']);
// });

// // NOTE sanctum testing
// Route::middleware('auth:sanctum')->get('/sanctum-test', function () {
//     return ['ok' => true, 'user' => request()->user()];
// });


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\DB;
use App\Models\Post;


// NOTE Guest API's
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::post('/login', [AuthController::class, 'login']);

// NOTE this is for anyone
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post}', [PostController::class, 'show']);

// NOTE Customer API's
Route::middleware('auth:sanctum')->group(function () {

    // NOTE user functions to manage their cart
    Route::get('/cart', [CartController::class, 'index']);
    Route::put('/cart/{itemId}', [CartController::class, 'update']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::delete('/cart/{itemId}', [CartController::class, 'destroy']);
    Route::delete('/cart', [CartController::class, 'clear']);

    // NOTE user functions to manage their wishlist
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{itemId}', [WishlistController::class, 'destroy']);

    // NOTE user functions to manage their orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);

    // NOTE common logout for user/admin
    Route::post('/logout', [AuthController::class, 'logout']);

    // NOTE this was a default route
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // NOTE this is for check sanctum is working
    Route::get('/sanctum-test', function () {
        return ['ok' => true, 'user' => request()->user()];
    });
});


// NOTE Admin API's
Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {

    // NOTE get counts for stat cards
    Route::get('/admin/products/count', [ProductController::class, 'countProducts']);
    Route::get('/admin/orders/count', [OrderController::class, 'countOrders']);
    Route::get('/admin/users/count', [UserController::class, 'countUsers']);
    Route::get('/admin/orders/revenue', [OrderController::class, 'confirmedRevenue']);

    // NOTE admin functions to manage products
    Route::get('/admin/products', [ProductController::class, 'adminIndex']);
    Route::get('/admin/products/{id}', [ProductController::class, 'show']);
    Route::post('/admin/products', [ProductController::class, 'store']);
    Route::put('/admin/products/{id}', [ProductController::class, 'update']);
    Route::delete('/admin/products/{id}', [ProductController::class, 'destroy']);

    // NOTE admin functions to manage categories
    Route::get('/admin/categories', [CategoryController::class, 'adminIndex']);
    Route::get('/admin/categories/{id}', [CategoryController::class, 'show']);
    Route::post('/admin/categories', [CategoryController::class, 'store']);
    Route::put('/admin/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/admin/categories/{id}', [CategoryController::class, 'destroy']);

    // NOTE admin functions to manage orders
    Route::get('/admin/orders', [OrderController::class, 'allOrders']);
    Route::get('/admin/orders/{id}', [OrderController::class, 'show']);
    Route::post('/admin/orders/{id}', [OrderController::class, 'updateStatus']);
    Route::delete('/admin/orders/{id}', [OrderController::class, 'destroy']);

    // NOTE admin functions to manage users
    Route::get('/admin/users', [UserController::class, 'index']);
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy']);

    // NOTE admin functions to manage the blog
    Route::get('/admin/posts', [PostController::class, 'adminIndex']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);
});


Route::post('/posts', function (Request $request) {
    $post = Post::create([
        'title' => $request->input('title'),
        'content' => $request->input('content'),
        'author' => $request->input('author'),
        'tags' => explode(',', $request->input('tags', '')),
        'published_at' => now(),
    ]);

    return response()->json([
        'status' => 'created',
        'data' => $post,
    ]);
});