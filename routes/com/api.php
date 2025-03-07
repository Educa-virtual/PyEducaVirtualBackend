<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\com\ComunicadosController;
use App\Http\Controllers\com\GruposController;

Route::group(['prefix' => 'com'], function () {
    Route::group(['prefix' => 'comunicado'], function () {
        Route::post('registrar_comunicado', [ComunicadosController::class, 'registrar']);
        Route::post('obtener_comunicado', [ComunicadosController::class, 'obtener']);
        Route::post('obtener_datos', [ComunicadosController::class, 'obtenerDatos']);
    });
    Route::group(['prefix' => 'miembros'], function () {
        Route::post('obtener_miembros', [GruposController::class, 'obtenerDatosMiembros']);
        Route::post('guardar_miembros', [GruposController::class, 'guardarMiembros']);
    });
});