<?php

use App\Http\Controllers\CultivosController;
use App\Http\Controllers\ErasController;
use App\Http\Controllers\EspeciesController;
use App\Http\Controllers\LotesController;
use App\Http\Controllers\PlantacionesController;
use App\Http\Controllers\SemillerosController;
use App\Http\Controllers\TiposEspecieController;
use App\Http\Controllers\TiposPlagaController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

//RUTAS USUARIOS

Route::prefix('usuarios')->group(function () {
    // Rutas públicas
    Route::post('/', [UsuariosController::class, 'store']);
    Route::post('/login', [UsuariosController::class, 'login']);
    Route::get('/reporteUsuarios', [UsuariosController::class, 'getTotalUsers']);
    
    // Ruta de refresh (¡debe ser pública!)
    Route::post('/refresh', [UsuariosController::class, 'refreshToken']);
    
    // Rutas protegidas por JWT
    Route::middleware('auth:api')->group(function () {
        Route::get('/', [UsuariosController::class, 'getAll']);
        Route::patch('/{identificacion}', [UsuariosController::class, 'update']);
        Route::get('/me', [UsuariosController::class, 'getCurrentUser']);
    });
});

//RUTAS TRAZABILIDAD

Route::prefix('lotes')->middleware('auth:api')->group(function () {
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

Route::middleware(['auth:api'])->group(function () {
    Route::get('/semilleros', [SemillerosController::class, 'index']);
    Route::post('/semilleros', [SemillerosController::class, 'store']);
    Route::patch('/semilleros/{id}', [SemillerosController::class, 'update']);
    Route::delete('/semilleros/{id}', [SemillerosController::class, 'destroy']);
});

Route::middleware('auth:api')->prefix('plantaciones')->group(function () {
    Route::get('/', [PlantacionesController::class, 'index']);
    Route::post('/', [PlantacionesController::class, 'store']);
    Route::patch('/{id}', [PlantacionesController::class, 'update']);
    Route::delete('/{id}', [PlantacionesController::class, 'destroy']);
    Route::get('/{id}', [PlantacionesController::class, 'show']);

    Route::get('/era/{fk_Eras}', [PlantacionesController::class, 'byEra']);
    Route::get('/cultivo/{fk_Cultivos}', [PlantacionesController::class, 'byCrop']);
    Route::get('/{fk_Eras}/{fk_Cultivos}', [PlantacionesController::class, 'byCropAndEra']);
});

//RUTAS FINANZAS
use App\Http\Controllers\ActividadesController;

Route::prefix('actividades')->middleware('auth:api')->group(function () {
    Route::get('/', [ActividadesController::class, 'index']);
    Route::post('/', [ActividadesController::class, 'store']);
    Route::get('/{id}', [ActividadesController::class, 'show']);
    Route::patch('/{id}', [ActividadesController::class, 'update']);
});

use App\Http\Controllers\CosechasController;

Route::prefix('cosechas')->middleware('auth:api')->group(function () {
    Route::get('/', [CosechasController::class, 'index']);
    Route::post('/', [CosechasController::class, 'store']);
    Route::get('/{id}', [CosechasController::class, 'show']);
    Route::patch('/{id}', [CosechasController::class, 'update']);
});

use App\Http\Controllers\DesechosController;

Route::prefix('desechos')->middleware('auth:api')->group(function () {
    Route::get('/', [DesechosController::class, 'index']);
    Route::post('/', [DesechosController::class, 'store']);
    Route::get('/{id}', [DesechosController::class, 'show']);
    Route::patch('/{id}', [DesechosController::class, 'update']);
});

use App\Http\Controllers\HerramientasController;

Route::prefix('herramientas')->middleware('auth:api')->group(function () {
    Route::get('/', [HerramientasController::class, 'index']);
    Route::post('/', [HerramientasController::class, 'store']);
    Route::get('/{id}', [HerramientasController::class, 'show']);
    Route::patch('/{id}', [HerramientasController::class, 'update']);
});

use App\Http\Controllers\InsumosController;

Route::prefix('insumos')->middleware('auth:api')->group(function () {
    Route::get('/', [InsumosController::class, 'index']);
    Route::post('/', [InsumosController::class, 'store']);
    Route::get('/{id}', [InsumosController::class, 'show']);
    Route::patch('/{id}', [InsumosController::class, 'update']);
});

use App\Http\Controllers\TiposDesechoController;

Route::prefix('tipos-desechos')->middleware('auth:api')->group(function () {
    Route::get('/', [TiposDesechoController::class, 'index']);
    Route::post('/', [TiposDesechoController::class, 'store']);
    Route::get('/{id}', [TiposDesechoController::class, 'show']);
    Route::patch('/{id}', [TiposDesechoController::class, 'update']);
});

use App\Http\Controllers\UsosHerramientasController;

Route::prefix('usosherramientas')->middleware('auth:api')->group(function () {
    Route::get('/', [UsosHerramientasController::class, 'index']);
    Route::post('/', [UsosHerramientasController::class, 'store']);
    Route::get('/{id}', [UsosHerramientasController::class, 'show']);
    Route::patch('/{id}', [UsosHerramientasController::class, 'update']);
});

use App\Http\Controllers\UsosProductosController;

Route::prefix('usosProductos')->middleware('auth:api')->group(function () {
    Route::get('/', [UsosProductosController::class, 'index']);
    Route::post('/', [UsosProductosController::class, 'store']);
    Route::get('/{id}', [UsosProductosController::class, 'show']);
    Route::patch('/{id}', [UsosProductosController::class, 'update']);
});

use App\Http\Controllers\VentasController;

Route::prefix('ventas')->middleware('auth:api')->group(function () {
    Route::get('/', [VentasController::class, 'index']);
    Route::post('/', [VentasController::class, 'store']);
    Route::get('/{id}', [VentasController::class, 'show']);
    Route::patch('/{id}', [VentasController::class, 'update']);
});

// RUTAS SANIDAD 

Route::prefix('tipoPlaga')->middleware('jwt.verify')->group(function () {
    Route::get('/', [TiposPlagaController::class, 'index']);
    Route::post('/', [TiposPlagaController::class, 'store']);
    Route::put('/{id}', [TiposPlagaController::class, 'update']);
    Route::get('/{id}', [TiposPlagaController::class, 'show']);
});

// RUTAS IoT

// Sensor
Route::prefix('sensores')->group(function () {
    Route::get('/', [\App\Http\Controllers\SensorController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\SensorController::class, 'store']);
    Route::get('/{id}', [\App\Http\Controllers\SensorController::class, 'show']);
});
// Umbral
Route::prefix('umbral')->group(function () {
    Route::get('/', [\App\Http\Controllers\UmbralController::class, 'index']);
    Route::post('/{sensorId}', [\App\Http\Controllers\UmbralController::class, 'store']);
    Route::put('/{id}', [\App\Http\Controllers\UmbralController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\UmbralController::class, 'destroy']);
});