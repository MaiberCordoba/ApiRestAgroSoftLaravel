<?php

use App\Http\Controllers\ActividadesController;

Route::prefix('actividades')->middleware('jwt.verify')->group(function () {
    Route::get('/', [ActividadesController::class, 'index']);
    Route::post('/', [ActividadesController::class, 'store']);
    Route::get('/{id}', [ActividadesController::class, 'show']);
    Route::patch('/{id}', [ActividadesController::class, 'update']);
});