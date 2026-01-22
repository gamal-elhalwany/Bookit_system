<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\ProductController;

/**
 * Authentication Routes.
 */
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify', [AuthController::class, 'verify']);
Route::post('/forgot-password', [AuthController::class, 'sendOtp']);
Route::post('/reset-password', [AuthController::class, 'resetWithOtp']);

Route::middleware(['auth:sanctum'])->group(function () {

    // --- Restaurants ---
    Route::get('/get-restaurants', [RestaurantController::class, 'index'])->name('get-Allrestaurants');
    Route::post('/create-restaurant', [RestaurantController::class, 'store'])->name('create-restaurant');
    Route::get('/get-restaurant/{id}', [RestaurantController::class, 'show'])->name('get-onerestaurant');
    Route::post('/update-restaurant/{restaurant}', [RestaurantController::class, 'update'])->name('update-restaurant');
    Route::delete('/delete-restaurant/{id}', [RestaurantController::class, 'delete'])->name('delete-restaurant');

    // --- Restaurant Images ---
    Route::get('/my-restaurants', [RestaurantController::class, 'myRestaurants']);
    Route::post('/create-image', [RestaurantController::class, 'storerestimage']);
    Route::get('/restaurant-images/{restaurant_id}', [RestaurantController::class, 'getrestimages']);
    Route::post('/update-image/{id}', [RestaurantController::class, 'updaterestimage']);
    Route::delete('/delete-image/{id}', [RestaurantController::class, 'deleterestimage']);

    // --- Subscriptions ---
    Route::get('/get-subscriptions', [SubscriptionController::class, 'index']);
    Route::get('/get-subscription/{id}', [SubscriptionController::class, 'show']);
    Route::post('/create-subscription', [SubscriptionController::class, 'store']);
    Route::post('/update-subscription/{id}', [SubscriptionController::class, 'update']);
    Route::delete('/delete-subscription/{id}', [SubscriptionController::class, 'delete']);

    // --- Comments ---
    Route::get('/get-comments', [CommentController::class, 'allcomments']);
    Route::get('/get-comment/{comment}', [CommentController::class, 'showcomment']);
    Route::post('/create-comment', [CommentController::class, 'storecomment']);
    Route::put('/update-comment/{comment}', [CommentController::class, 'updatecomment']);
    Route::delete('/delete-comment/{comment}', [CommentController::class, 'deletecomment']);

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
