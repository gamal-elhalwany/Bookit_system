<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PackagesController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\SubscriptionPaymentController;
use App\Http\Controllers\Api\our_website\ProductsController;
use App\Http\Controllers\Api\our_website\RestaurantsController;

/**
 * Authentication Routes.
 */
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify', [AuthController::class, 'verify']);
Route::post('/forgot-password', [AuthController::class, 'forgetPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPasswordWithOtp']);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // --- Create Restaurant ---
    Route::post('/create-restaurant', [RestaurantController::class, 'store'])->name('create-restaurant');
    Route::get('/get-restaurants', [RestaurantController::class, 'index'])->name('get-Allrestaurants');

    // everything inside this group requires passing through the subscription flow check.
    // Here goes all owner dashboard routes that require an active subscription.
    Route::middleware(['subscription.flow'])->group(function () {
        Route::get('stats', [SubscriptionController::class, 'stats']);

        // --- Restaurants ---
        Route::get('/get-restaurant/{id}', [RestaurantController::class, 'show'])->name('get-onerestaurant');
        Route::post('/update-restaurant/{restaurant}', [RestaurantController::class, 'update'])->name('update-restaurant');
        Route::delete('/delete-restaurant/{id}', [RestaurantController::class, 'delete'])->name('delete-restaurant');

        // --- Restaurant Images ---
        Route::post('/add-restaurant-image', [RestaurantController::class, 'storerestimage']);
        Route::get('get-restaurant-images/{id}', [RestaurantController::class, 'getrestimages']);
        Route::delete('/delete-restaurant-image/{id}', [RestaurantController::class, 'deleterestimage']);

        // --- Subscriptions Checkout ---
        Route::post('/subscribe/checkout', [SubscriptionController::class, 'checkout']);

        // --- Categories ---
        Route::get('/categories', [CategoriesController::class, 'index']);
        Route::post('/categories', [CategoriesController::class, 'store']);
        Route::post('/category/{category}', [CategoriesController::class, 'update']);
        Route::delete('/delete/{category}', [CategoriesController::class, 'destroy']);

        // --- Products ---
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::get('product/show/{product}', [ProductController::class, 'show']);
        Route::post('/product/{product}', [ProductController::class, 'update']);
        Route::delete('product/delete/{product}', [ProductController::class, 'destroy']);
    });

    // --- Packages ---
    Route::get('/get-packages', [PackagesController::class, 'index']);
    Route::post('/store-package', [PackagesController::class, 'store']);
    Route::get('/get-package/{package}', [PackagesController::class, 'show']);
    Route::post('/update-package/{package}', [PackagesController::class, 'update']);
    Route::delete('/delete-package/{package}', [PackagesController::class, 'destroy']);

    // --- Subscriptions ---
    Route::get('/get-subscriptions', [SubscriptionController::class, 'index']);
    Route::get('/get-subscription/{id}', [SubscriptionController::class, 'show']);
    Route::delete('/delete-subscription/{id}', [SubscriptionController::class, 'delete']);

    // --- Comments ---
    Route::get('/get-comments', [CommentController::class, 'index']);
    Route::get('/get-comment/{comment}', [CommentController::class, 'show']);
    Route::post('/create-comment', [CommentController::class, 'store']);
    Route::post('/update-comment/{comment}', [CommentController::class, 'update']);
    Route::delete('/delete-comment/{comment}', [CommentController::class, 'destroy']);

    // --- Roles ---
    // Route::resource('roles', RolesController::class);
});


/**
 * Our Home Page Routes.
 */
/*============================== Get all products of all restaurants =============================*/
Route::get('get-all-products', [ProductsController::class, 'index']);

/*============================== Get all restaurants. =============================*/
Route::get('top-rated-restaurants', [RestaurantsController::class, 'index']);

// رابط الـ Webhook (ده اللي بتحطه في داشبورد Paymob)
Route::any('/payments/callback', [SubscriptionPaymentController::class, 'handleWebhook']);