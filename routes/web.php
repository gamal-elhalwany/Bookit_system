<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/restaurants11', function () {
    return response()->json([
        ['id' => 1, 'name' => 'Pizza Hut'],
        ['id' => 2, 'name' => 'KFC'],
        ['id' => 3, 'name' => 'McDonalds'],
    ]);
});

