<?php

use App\Http\Controllers\ErasController;
use App\Http\Controllers\LotesController;
use App\Http\Controllers\UsuariosController;
<<<<<<< Karen
use App\Http\Controllers;
=======
>>>>>>> master
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

<<<<<<< Karen
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
=======
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

Route::prefix('eras')->middleware('auth:api')->group(function () {
    Route::get('/', [ErasController::class, 'index'])->name('index');
    Route::get('/{id}', [ErasController::class, 'show'])->name('show');
    Route::post('/', [ErasController::class, 'store'])->name('store');
    Route::patch('/{id}', [ErasController::class, 'update'])->name('update');
    Route::delete('/{id}', [ErasController::class, 'destroy'])->name('destroy');
    Route::get('/reporte/{id}', [ErasController::class, 'getByLoteId'])->name('reporte');
});
>>>>>>> master
