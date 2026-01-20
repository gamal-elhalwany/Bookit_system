<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\SubscriptionController;


///////////////Login/////////////////////////
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify', [AuthController::class, 'verify']);
Route::post('/forgot-password', [AuthController::class, 'sendOtp']);
Route::post('/reset-password', [AuthController::class, 'resetWithOtp']);

//////////////////////////////////////////////



///////////////////////////Restaurantsالمطاعم//////////////////////////////////////////
/////////////(name)|(address)|(phone)|(email)|(image)|(opening_time)|(closing_time)|business_type|
Route::get('/get-restaurants', [RestaurantController::class, 'index'])->name('get-Allrestaurants');
// إضافة مطعم جديد
Route::middleware('auth:sanctum')->post('/create-restaurant', [RestaurantController::class, 'store']);
// عرض مطعم واحد حسب الـ id
Route::get('/get-restaurant/{id}', [RestaurantController::class, 'show'])->name('get-onerestaurant');
// تعديل بيانات مطعم
Route::middleware('auth:sanctum')->put('/update-restaurant/{id}', [RestaurantController::class, 'update'])->name('update-restaurant');

// حذف مطعم
Route::middleware('auth:sanctum')->delete('/delete-restaurant/{id}', [RestaurantController::class, 'delete'])->name('delete-restaurant');
///////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////Subscription الباقات//////////////////////////////////////////
//////////////////////// (name)| (description)| (price)| (duration_days)| (is_active)///
///عرض الباقات
Route::get('/get-subscriptions', [SubscriptionController::class, 'index']);
//عرض باقه واحده
Route::get('/get-subscription/{id}', [SubscriptionController::class, 'show']);
// اضافة باقه
Route::middleware('auth:sanctum')->post('/create-subscription', [SubscriptionController::class, 'store']);

// تحديث باقه
Route::middleware('auth:sanctum')->put('/update-subscription/{id}', [SubscriptionController::class, 'update']);

// حذف باقه
Route::middleware('auth:sanctum')->delete('/delete-subscription/{id}', [SubscriptionController::class, 'delete']);
/////////////////////////////////////////////////////////////////////////////////////////
////////////////////////Commentsالكومنتات/(name)|(job_title)|(rate)|(comment)//////////////////////////////////

Route::get('/get-comments', [RestaurantController::class, 'allcomments']);       // عرض كل الكومنتات
Route::get('/get-comment/{comment}', [RestaurantController::class, 'showcomment']); // عرض كومنت واحد
Route::post('/create-comment', [RestaurantController::class, 'storecomment']);      // إضافة كومنت جديد
Route::put('/update-comment/{comment}', [RestaurantController::class, 'updatecomment']); // تحديث كومنت
Route::delete('/delete-comment/{comment}', [RestaurantController::class, 'deletecomment']); // حذف كومنت
////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////RestaurantImages صور المطعم//////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/my-restaurants', [RestaurantController::class, 'myRestaurants']);///سيليكت بوكس لاختيار المطعم
Route::middleware('auth:sanctum')->post('/create-image', [RestaurantController::class, 'storerestimage']);//اضافة مطعم
Route::get('/restaurant-images/{restaurant_id}', [RestaurantController::class, 'getrestimages']);///كل صور الخاصه بالمطعم
Route::middleware('auth:sanctum')->put('/update-image/{id}', [RestaurantController::class, 'updaterestimage']);///تحديث مطعم
Route::middleware('auth:sanctum')->delete('/delete-image/{id}', [RestaurantController::class, 'deleterestimage']);/////حذف مطعم
///////////////////////////////////////////////////////////////////////////







