<?php

use App\Http\Controllers\CourseManagement\CoursController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagement\AuthController;
use App\Http\Controllers\SchoolManagement\AutoEcoleController;
use App\Http\Controllers\SchoolManagement\AbonnementController;
use App\Http\Controllers\SchoolManagement\PaiementController;
use App\Http\Controllers\PublicAccess\ProprietaireInscriptionController;
use App\Http\Controllers\UserManagement\RoleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Organisation par modules :
| - Authentification
| - Auto-écoles & abonnements
| - Paiements
*/

// 🎯 Inscription spéciale d’un propriétaire d’auto-école
Route::post('/auth/register-proprietaire', [ProprietaireInscriptionController::class, 'register']);

// 🎭 Liste des rôles disponibles (publique pour inscription)
Route::get('/roles', [RoleController::class, 'index']);

// 🔐 Authentification
Route::post('/inscription', [AuthController::class, 'register']);
Route::post('/connexion', [AuthController::class, 'login']);
// 🔐 Reset mot de passe
Route::post('/mot-de-passe/oubli', [AuthController::class, 'sendResetLinkEmail']);
Route::post('/mot-de-passe/reset', [AuthController::class, 'resetPassword']);

// 🔒 Update mot de passe (authentifié)
Route::middleware('auth:sanctum')->post('/mot-de-passe/update', [AuthController::class, 'updatePassword']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profil', [AuthController::class, 'me']);
    Route::post('/deconnexion', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | 🔐 Routes protégées : accès uniquement aux utilisateurs liés à une auto-école
    |--------------------------------------------------------------------------
    */
    Route::middleware('has.autoecole')->group(function () {
        // 🏫 Auto-écoles
        Route::prefix('auto-ecoles')->group(function () {
            Route::get('/', [AutoEcoleController::class, 'index']);
            Route::post('/', [AutoEcoleController::class, 'store']);
            Route::get('/{id}', [AutoEcoleController::class, 'show']);
            Route::put('/{id}', [AutoEcoleController::class, 'update']);
            Route::delete('/{id}', [AutoEcoleController::class, 'destroy']);
            Route::patch('/{id}/toggle-status', [AutoEcoleController::class, 'toggleStatus']);
        });

        // 📦 Abonnements
        Route::prefix('abonnements')->group(function () {
            Route::get('/', [AbonnementController::class, 'index']);
            Route::get('/filtrer', [AbonnementController::class, 'filtrer']);
            Route::post('/', [AbonnementController::class, 'store']);
            Route::put('/{id}', [AbonnementController::class, 'update']);
            Route::delete('/{id}', [AbonnementController::class, 'destroy']);
        });

        // 💳 Paiements
        Route::prefix('school-management')->group(function () {
            Route::apiResource('paiements', PaiementController::class);
        });
        // 📚 Gestion des Cours
        Route::prefix('cours')->group(function () {
            Route::get('/', [CoursController::class, 'index']);               // Liste des cours
            Route::post('/', [CoursController::class, 'store']);              // Planifier un cours (Admin/Moniteur)
            Route::get('/{cour}', [CoursController::class, 'show']);          // Détails d'un cours
            Route::put('/{cour}', [CoursController::class, 'update']);        // Modifier un cours
            Route::delete('/{cour}', [CoursController::class, 'destroy']);    // Supprimer un cours

            Route::patch('/{cour}/reserve', [CoursController::class, 'reserve']); // Réserver un cours (Élève)
            Route::patch('/{cour}/cancel', [CoursController::class, 'cancel']);   // Annuler une réservation (Élève/Admin)
            Route::patch('/{cour}/finish', [CoursController::class, 'finish']);   // Terminer un cours (Moniteur/Admin)
            Route::patch('/{cour}/cancel-definitive', [CoursController::class, 'cancelDefinitive']); // Annuler définitivement (Admin)
        });
    });
});
