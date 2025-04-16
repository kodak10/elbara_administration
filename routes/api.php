<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

    // Authentification
    Route::post('/check-phone', [AuthController::class, 'checkPhone']);

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'getUser']);
    
    // Gestion des commandes
    Route::post('/orders', [OrderController::class, 'store']);
   // Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::get('/{user_id}/orders', [OrderController::class, 'getUserOrders']);
    Route::get('/order/{orderId}', [OrderController::class, 'getOrderDetails']);

    Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);

// Routes protégées par auth:sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Routes nécessitant une authentification
});

