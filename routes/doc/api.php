<?php

use App\Http\Controllers\doc\MaterialEducativosController;
use App\Http\Controllers\doc\PortafoliosController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'doc', 'middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'portafolio'], function () {
        Route::post('guardar_cuaderno', [PortafoliosController::class, 'guardarCuadernoCampo']);
    });

    Route::group(['prefix' => 'material-educativo'], function () {
        Route::post('guardar', [MaterialEducativosController::class, 'store']);
        Route::post('obtener', [MaterialEducativosController::class, 'list']);
        Route::post('eliminar', [MaterialEducativosController::class, 'delete']);
        Route::post('subir-archivo', [MaterialEducativosController::class, 'subirArchivo']);
    });

});