<?php

use App\Http\Controllers\CultivosController;
use App\Http\Controllers\ErasController;
use App\Http\Controllers\EspeciesController;
use App\Http\Controllers\LotesController;
use App\Http\Controllers\TiposEspecieController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

//RUTAS USUARIOS

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


//RUTAS TRAZABILIDAD

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

Route::prefix('tiposEspecie')->middleware('auth:api')->group(function () {
    Route::get('/', [TiposEspecieController::class, 'index'])->name('tipos.index');
    Route::post('/', [TiposEspecieController::class, 'store'])->name('tipos.store');
    Route::patch('/{id}', [TiposEspecieController::class, 'update'])->name('tipos.update');
    Route::delete('/{id}', [TiposEspecieController::class, 'destroy'])->name('tipos.destroy');
});

Route::middleware('auth:api')->prefix('especies')->group(function () {
    Route::get('/', [EspeciesController::class, 'index']);
    Route::post('/', [EspeciesController::class, 'store']);
    Route::patch('/{id}', [EspeciesController::class, 'update']);
    Route::delete('/{id}', [EspeciesController::class, 'destroy']);
});

Route::middleware(['auth:api'])->group(function () {
    Route::get('/cultivos', [CultivosController::class, 'index']);
    Route::post('/cultivos', [CultivosController::class, 'store']);
    Route::get('/cultivos/{id}', [CultivosController::class, 'show']);
    Route::patch('/cultivos/{id}', [CultivosController::class, 'update']);
    Route::delete('/cultivos/{id}', [CultivosController::class, 'destroy']);
    Route::get('/cultivos/especie/{fk_Especies}', [CultivosController::class, 'porEspecie']);
    Route::get('/cultivos/siembra/{fechaSiembra}', [CultivosController::class, 'porSiembra']);
    Route::get('/reporte/cultivos/activos', [CultivosController::class, 'reporteActivos']);
});

// RUTAS IoT

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
