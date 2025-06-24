<?php

use App\Http\Controllers\UsuariosController;

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