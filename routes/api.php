<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\RestaurantController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/who-am-i', [AuthController::class, 'show']);

    // Route::resource('restaurants', RestaurantController::class)->only(['index', 'show']);
    Route::get('/restaurants', [RestaurantController::class, 'index']);
    Route::get('/restaurants/{restaurant}/menu', [RestaurantController::class, 'show']);

    Route::post('orders', [OrderController::class, 'store']);
});

Route::resource('restaurants/{restaurant}/orders', OrderController::class)->only(['index', 'show', 'update']);
