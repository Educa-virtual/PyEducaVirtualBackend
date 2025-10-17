<?php

use App\Http\Controllers\repo\ArchivosController;
use App\Http\Controllers\repo\CarpetasController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'repo'], function () {
  Route::group(['prefix' => 'carpetas'], function () {
    Route::post('', [CarpetasController::class, 'guardarCarpeta']);
    Route::get('', [CarpetasController::class, 'obtenerCarpetas']);
    Route::put('', [CarpetasController::class, 'actualizarCarpeta']);
    Route::delete('', [CarpetasController::class, 'eliminarCarpeta']);
  });
  Route::group(['prefix' => 'archivos'], function () {
    Route::post('', [ArchivosController::class, 'guardarArchivo']);
    Route::get('/descargar/{iArchivoId}', [ArchivosController::class, 'descargarArchivo']);
    Route::delete('/{iArchivoId}', [ArchivosController::class, 'eliminarArchivo']);
  });
});
