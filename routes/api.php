<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolManagement\AutoEcoleController;
use App\Http\Controllers\SchoolManagement\AbonnementController;
use App\Http\Controllers\UserManagement\AuthController;
use App\Http\Controllers\SchoolManagement\PaiementController;

Route::prefix('school-management')->group(function () {
    Route::apiResource('paiements', PaiementController::class);
});

// Route::patch('/auto-ecoles/{id}/toggle-status', [AutoEcoleController::class, 'toggleStatus']);

Route::prefix('abonnements')->group(function () {
    Route::get('/', [AbonnementController::class, 'index']);
    Route::post('/', [AbonnementController::class, 'store']);
});


Route::prefix('auto-ecoles')->group(function () {
    Route::get('/', [AutoEcoleController::class, 'index']);
    Route::post('/', [AutoEcoleController::class, 'store']);
    Route::get('/{id}', [AutoEcoleController::class, 'show']);
    Route::put('/{id}', [AutoEcoleController::class, 'update']);
    Route::delete('/{id}', [AutoEcoleController::class, 'destroy']);
    Route::patch('{id}/toggle-status', [AutoEcoleController::class, 'toggleStatus']); // ✅ Correction ici
});



Route::post('/inscription', [AuthController::class, 'register']);
Route::post('/connexion', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profil', [AuthController::class, 'me']);
    Route::post('/deconnexion', [AuthController::class, 'logout']);
});
