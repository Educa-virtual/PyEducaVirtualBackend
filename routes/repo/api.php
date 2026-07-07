<?php

use App\Http\Controllers\repo\ArchivosController;
use App\Http\Controllers\repo\CarpetasController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'repo'], function () {

    Route::post('listarCarpetas', [CarpetasController::class, 'listarCarpetas']);
    Route::post('verCarpeta', [CarpetasController::class, 'verCarpeta']);
    Route::post('guardarCarpeta', [CarpetasController::class, 'guardarCarpeta']);
    Route::post('actualizarCarpeta', [CarpetasController::class, 'actualizarCarpeta']);
    Route::post('eliminarCarpeta', [CarpetasController::class, 'eliminarCarpeta']);
    Route::post('verReporteCarpetas', [CarpetasController::class, 'verReporteCarpetas']);

    Route::post('guardarArchivo', [ArchivosController::class, 'guardarArchivo']);
    Route::post('descargarArchivo', [ArchivosController::class, 'descargarArchivo']);
    Route::post('eliminarArchivo', [ArchivosController::class, 'eliminarArchivo']);
});
