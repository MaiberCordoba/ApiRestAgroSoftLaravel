<?php

use App\Http\Controllers\LotesController;
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

Route::prefix('lotes')->middleware('jwt.verify')->group(function () {
    // CRUD 
    Route::get('/', [LotesController::class, 'index']);
    Route::get('/{id}', [LotesController::class, 'show']);
    Route::post('/', [LotesController::class, 'store']);
    Route::patch('/{id}', [LotesController::class, 'update']);
    Route::delete('/{id}', [LotesController::class, 'destroy']);

    // Búsquedas específicas
    Route::get('/ubicacion/{posX}/{posY}', [LotesController::class, 'getByUbicacion']);
    Route::get('/estado/{estado}', [LotesController::class, 'getByEstado']);

    // Reportes
    Route::get('/reporte/dimensiones/{tamX}/{tamY}', [LotesController::class, 'getByDimensiones']);
});