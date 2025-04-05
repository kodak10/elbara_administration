<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\GareController;
use App\Http\Controllers\LivreurController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;





Route::get('/', function () {
    return view('pages.login');
});


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');



Route::get('/commandes', [OrderController::class, 'index'])->name('orders.index');
Route::get('/commandes/statut/{statut}', [OrderController::class, 'filtrerParStatut'])->name('orders.filtrer');
Route::get('/commandes/historique', [OrderController::class, 'historique'])->name('orders.historique');
Route::get('/commandes/{id}', [OrderController::class, 'show'])->name('orders.show');

Route::post('/orders/{order}/assign', [OrderController::class, 'assign'])->name('orders.assign');
Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

Route::put('/livreurs/{livreur}/approuver', [LivreurController::class, 'approuver'])->name('livreurs.approuver');

Route::get('/livreurs/demandes', [LivreurController::class, 'demandes'])->name('livreurs.demandes');
Route::get('/livreurs/{id}/toggle-status', [LivreurController::class, 'updateStatus'])->name('livreurs.toggleStatus');

Route::get('/livreurs/financier', [FinanceController::class, 'pointLivreur'])->name('livreurs.financier');


Route::resource('livreurs', LivreurController::class);



Route::resource('companies', CompanyController::class);
Route::resource('gares', GareController::class);

Route::get('/utilisateurs', [UserController::class, 'index'])->name('utilisateurs.index');
Route::post('/utilisateurs', [UserController::class, 'store'])->name('utilisateurs.store');
Route::get('/utilisateurs/{id}/toggle-status', [UserController::class, 'updateStatus'])->name('utilisateurs.toggleStatus');

Route::get('/profil', [UserController::class, 'profil'])->name('profil.edit');
Route::post('/profil', [UserController::class, 'updateProfile'])->name('profil.update');

Route::get('/activity', [ActivityController::class, 'index'])->name('activity.index');



Route::get('/services', function () {
    return view('pages.services.index');
});





Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


