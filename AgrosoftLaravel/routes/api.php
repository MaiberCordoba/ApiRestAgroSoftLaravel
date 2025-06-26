<?php

use App\Http\Controllers\UsuariosController;
use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::prefix('usuarios')->group(function () {
    // Rutas públicas
    Route::post('/', [UsuariosController::class, 'store']);
    Route::post('/login', [UsuariosController::class, 'login']);
    Route::get('/stats', [UsuariosController::class, 'getTotalUsers']);
    
    // Ruta de refresh (¡debe ser pública!)
    Route::post('/refresh', [UsuariosController::class, 'refreshToken']);
    
    // Rutas protegidas por JWT
    Route::middleware('jwt.verify')->group(function () {
        Route::get('/', [UsuariosController::class, 'getAll']);
        Route::patch('/{identificacion}', [UsuariosController::class, 'update']);
        Route::get('/me', [UsuariosController::class, 'getCurrentUser']);
    });
});

// Sensor
Route::prefix('sensores')->group(function () {
    Route::get('/', [\App\Http\Controllers\SensorController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\SensorController::class, 'store']);
    Route::get('/{id}', [\App\Http\Controllers\SensorController::class, 'show']);
});
// Umbral
Route::prefix('umbrales')->group(function () {
    Route::post('/{sensorId}', [\App\Http\Controllers\UmbralController::class, 'store']);
    Route::put('/{id}', [\App\Http\Controllers\UmbralController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\UmbralController::class, 'destroy']);
});
