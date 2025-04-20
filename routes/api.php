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
    Route::post('/demande-livreur', [LivreurController::class, 'demandeLivreur']);

    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    //Route::post('/logout', [AuthController::class, 'logout']);
    //Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);


    Route::middleware('auth:sanctum')->group(function () {
        // Authentification
        Route::get('/user', [AuthController::class, 'getUser']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);
    
        // Gestion des commandes
        Route::get('/livreur/orders', [OrderController::class, 'livreurOrders']); // Modifié
        Route::post('/orders', [OrderController::class, 'store']);
        Route::post('/livreur/orders/{order}/cancel', [OrderController::class, 'cancelOrder']);


        Route::prefix('livreurs')->group(function () {
            Route::get('/info', [LivreurController::class,  'getLivreurInfo']);
            //Route::get('/stats/{userId}', [LivreurController::class, 'getOrderStats']);
            // Route::get('/pending/{userId}', [LivreurController::class, 'getPendingOrders']);
            // Route::get('/in-progress/{userId}', [LivreurController::class, 'getInProgressOrders']);
            Route::post('/update-status', [LivreurController::class, 'updateStatus']);




            // Informations du livreur
            
            // Statistiques des commandes
            Route::get('/stats/{userId}', [OrderController::class, 'getOrderStats']);
            
            // Commandes par statut
            Route::get('/orders', [OrderController::class, 'getOrdersByStatus']);
            
            // Annuler une commande
            Route::post('/orders/{orderId}/cancel', [OrderController::class, 'cancelOrder']);

        });
        

        

    });


