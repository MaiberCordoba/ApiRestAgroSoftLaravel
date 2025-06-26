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

use App\Http\Controllers\ActividadesController;

Route::prefix('actividades')->middleware('jwt.verify')->group(function () {
    Route::get('/', [ActividadesController::class, 'index']);
    Route::post('/', [ActividadesController::class, 'store']);
    Route::get('/{id}', [ActividadesController::class, 'show']);
    Route::patch('/{id}', [ActividadesController::class, 'update']);
});

use App\Http\Controllers\CosechasController;

Route::prefix('cosechas')->middleware('jwt.verify')->group(function () {
    Route::get('/', [CosechasController::class, 'index']);
    Route::post('/', [CosechasController::class, 'store']);
    Route::get('/{id}', [CosechasController::class, 'show']);
    Route::patch('/{id}', [CosechasController::class, 'update']);
});

use App\Http\Controllers\DesechosController;

Route::prefix('desechos')->middleware('jwt.verify')->group(function () {
    Route::get('/', [DesechosController::class, 'index']);
    Route::post('/', [DesechosController::class, 'store']);
    Route::get('/{id}', [DesechosController::class, 'show']);
    Route::patch('/{id}', [DesechosController::class, 'update']);
});

use App\Http\Controllers\HerramientasController;

Route::prefix('herramientas')->middleware('jwt.verify')->group(function () {
    Route::get('/', [HerramientasController::class, 'index']);
    Route::post('/', [HerramientasController::class, 'store']);
    Route::get('/{id}', [HerramientasController::class, 'show']);
    Route::patch('/{id}', [HerramientasController::class, 'update']);
});

use App\Http\Controllers\InsumosController;

Route::prefix('insumos')->middleware('jwt.verify')->group(function () {
    Route::get('/', [InsumosController::class, 'index']);
    Route::post('/', [InsumosController::class, 'store']);
    Route::get('/{id}', [InsumosController::class, 'show']);
    Route::patch('/{id}', [InsumosController::class, 'update']);
});

use App\Http\Controllers\TiposDesechoController;

Route::prefix('tiposDesecho')->middleware('jwt.verify')->group(function () {
    Route::get('/', [TiposDesechoController::class, 'index']);
    Route::post('/', [TiposDesechoController::class, 'store']);
    Route::get('/{id}', [TiposDesechoController::class, 'show']);
    Route::patch('/{id}', [TiposDesechoController::class, 'update']);
});

use App\Http\Controllers\UsosHerramientasController;

Route::prefix('usosHerramientas')->middleware('jwt.verify')->group(function () {
    Route::get('/', [UsosHerramientasController::class, 'index']);
    Route::post('/', [UsosHerramientasController::class, 'store']);
    Route::get('/{id}', [UsosHerramientasController::class, 'show']);
    Route::patch('/{id}', [UsosHerramientasController::class, 'update']);
});

use App\Http\Controllers\UsosProductosController;

Route::prefix('usosProductos')->middleware('jwt.verify')->group(function () {
    Route::get('/', [UsosProductosController::class, 'index']);
    Route::post('/', [UsosProductosController::class, 'store']);
    Route::get('/{id}', [UsosProductosController::class, 'show']);
    Route::patch('/{id}', [UsosProductosController::class, 'update']);
});

use App\Http\Controllers\VentasController;

Route::prefix('ventas')->middleware('jwt.verify')->group(function () {
    Route::get('/', [VentasController::class, 'index']);
    Route::post('/', [VentasController::class, 'store']);
    Route::get('/{id}', [VentasController::class, 'show']);
    Route::patch('/{id}', [VentasController::class, 'update']);
});