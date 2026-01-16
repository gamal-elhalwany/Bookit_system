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
/////////////(user_id)|(name)|(address)|(phone)|(email)|(image)|(opening_time)|(closing_time)|business_type|
Route::get('/get-restaurants', [RestaurantController::class, 'index'])->name('get-Allrestaurants');
// إضافة مطعم جديد
Route::post('/create-restaurant', [RestaurantController::class, 'store'])->name('create-restaurant');
// عرض مطعم واحد حسب الـ id
Route::get('/get-restaurant/{id}', [RestaurantController::class, 'show'])->name('get-onerestaurant');
// تعديل بيانات مطعم
Route::put('/update-restaurant/{id}', [RestaurantController::class, 'update'])->name('update-restaurant');
// حذف مطعم
Route::delete('/delete-restaurant/{id}', [RestaurantController::class, 'delete'])->name('delete-restaurant');
///////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////Subscription الباقات//////////////////////////////////////////
//////////////////////// (name)| (description)| (price)| (duration_days)| (is_active)///
//اضافة باقه
Route::post('/create-subscription', [SubscriptionController::class, 'store']);
///عرض الباقات
Route::get('/get-subscriptions', [SubscriptionController::class, 'index']);
//عرض باقه واحده
Route::get('/get-subscription/{id}', [SubscriptionController::class, 'show']);
//تحديث باقه
Route::put('/update-subscription/{id}', [SubscriptionController::class, 'update']);
//حذف باقه
Route::delete('/delete-subscription/{id}', [SubscriptionController::class, 'delete']);
/////////////////////////////////////////////////////////////////////////////////////////














