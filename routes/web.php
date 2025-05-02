<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\GareController;
use App\Http\Controllers\LivreurController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PartenaireController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.login');
});


// Groupe pour les utilisateurs authentifiés ET actifs
Route::middleware(['auth', 'active.user'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // Commandes
    Route::prefix('/commandes')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/statut/{statut}', [OrderController::class, 'filtrerParStatut'])->name('orders.filtrer');
        Route::get('/historique', [OrderController::class, 'historique'])->name('orders.historique');
        Route::get('/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/{order}/assign', [OrderController::class, 'assign'])->name('orders.assign');
        Route::patch('/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    });
    
    // Livreurs
    Route::prefix('/livreurs')->group(function () {
        Route::put('/{livreur}/approuver', [LivreurController::class, 'approuver'])->name('livreurs.approuver');
        Route::post('/{id}/refuser', [LivreurController::class, 'refuser'])->name('livreurs.refuser');
        Route::get('/demandes', [LivreurController::class, 'demandes'])->name('livreurs.demandes');
        Route::get('/{id}/toggle-status', [LivreurController::class, 'updateStatus'])->name('livreurs.toggleStatus');
        Route::get('/financier', [FinanceController::class, 'pointLivreur'])->name('livreurs.financier');
    });
    Route::resource('livreurs', LivreurController::class);
    
    // Ressources
    Route::resource('companies', CompanyController::class);
    Route::resource('gares', GareController::class);
    
    // Utilisateurs
    Route::prefix('/utilisateurs')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('utilisateurs.index');
        Route::post('/', [UserController::class, 'store'])->name('utilisateurs.store');
        Route::get('/{id}/toggle-status', [UserController::class, 'updateStatus'])->name('utilisateurs.toggleStatus');
    });
    
    // Profil
    Route::prefix('/profil')->group(function () {
        Route::get('/', [UserController::class, 'profil'])->name('profil');
        Route::put('/update', [UserController::class, 'updateProfile'])->name('profil.update');
        Route::delete('/avatar', [UserController::class, 'destroyAvatar'])->name('profil.avatar.destroy');
    });
    
    // Activité
    Route::get('/activity', [ActivityController::class, 'index'])->name('activity.index');
    
    // Partenaires
    Route::resource('partenaires', PartenaireController::class);
    
    // Services
    Route::get('/services', function () {
        return view('pages.services.index');
    });
});


Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


