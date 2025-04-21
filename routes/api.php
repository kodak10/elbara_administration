<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LivreurController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;
use App\Models\Livreur;
use App\Models\Order;
use Database\Seeders\LivreurSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

    // Authentification
    Route::post('/check-phone', [AuthController::class, 'checkPhone']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

    Route::post('/demande-livreur', [LivreurController::class, 'demandeLivreur']);

    Route::middleware('auth:sanctum')->group(function () {

        // Authentification
        Route::get('/user', [AuthController::class, 'getUser']);
        Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);

        Route::post('/update-profile', [UserController::class, 'update']);

        Route::post('/update-profile-image', [UserController::class, 'updateProfileImage']);

    
        // Gestion des commandes
        Route::post('/orders', [OrderController::class, 'store']);

        Route::prefix('livreurs')->group(function () {

            Route::get('/info', [LivreurController::class,  'getLivreurInfo']);
            Route::get('/orders', [OrderController::class, 'livreurOrders']);

            // Statistiques des commandes
            Route::get('/stats/{userId}', [LivreurController::class, 'getOrderStats']);

            // Commandes par statut
            Route::get('/orders', [LivreurController::class, 'getOrdersByStatus']);

            // Annuler une commande
            Route::post('/orders/{order}/cancel', [OrderController::class, 'cancelOrder']);
            
            // Statut du livreur en ligne pas en ligne
            Route::post('/update-status', [LivreurController::class, 'updateStatus']);

        });
        
    });


