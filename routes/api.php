<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagement\AuthController;
use App\Http\Controllers\SchoolManagement\AutoEcoleController;
use App\Http\Controllers\SchoolManagement\AbonnementController;
use App\Http\Controllers\SchoolManagement\PaiementController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Organisation par modules :
| - Authentification
| - Auto-écoles & abonnements
| - Paiements
*/

// 🔐 Authentification (Inscription, Connexion, Profil)
Route::post('/inscription', [AuthController::class, 'register']);
Route::post('/connexion', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profil', [AuthController::class, 'me']);
    Route::post('/deconnexion', [AuthController::class, 'logout']);
});

// 🏫 Auto-écoles
Route::prefix('auto-ecoles')->group(function () {
    Route::get('/', [AutoEcoleController::class, 'index']);
    Route::post('/', [AutoEcoleController::class, 'store']);
    Route::get('/{id}', [AutoEcoleController::class, 'show']);
    Route::put('/{id}', [AutoEcoleController::class, 'update']);
    Route::delete('/{id}', [AutoEcoleController::class, 'destroy']);
    Route::patch('{id}/toggle-status', [AutoEcoleController::class, 'toggleStatus']); // Activation/Désactivation
});

// 📦 Abonnements
Route::prefix('abonnements')->group(function () {
    Route::get('/', [AbonnementController::class, 'index']);
    Route::get('/filtrer', [AbonnementController::class, 'filtrer']); // 🔍 Nouveau
    Route::post('/', [AbonnementController::class, 'store']);
    Route::put('/{id}', [AbonnementController::class, 'update']);       // 🔄 Nouveau
    Route::delete('/{id}', [AbonnementController::class, 'destroy']);   // 🗑️ Nouveau
});


// 💳 Paiements
Route::prefix('school-management')->group(function () {
    Route::apiResource('paiements', PaiementController::class);
});
