<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Routes publiques (sans authentification)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
});

// Routes protégées (nécessitent une authentification)
Route::middleware('auth:sanctum')->group(function () {
    
    // Authentification
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });

    // Routes pour les étudiants
    Route::middleware('role:student')->prefix('student')->group(function () {
        // À implémenter : commandes, parrainage, etc.
    });

    // Routes pour les employés
    Route::middleware('role:employee')->prefix('employee')->group(function () {
        // À implémenter : validation des commandes, etc.
    });

    // Routes pour les gérants
    Route::middleware('role:manager')->prefix('manager')->group(function () {
        // À implémenter : gestion des employés, etc.
    });

    // Routes pour les administrateurs
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // À implémenter : gestion complète, etc.
    });
});