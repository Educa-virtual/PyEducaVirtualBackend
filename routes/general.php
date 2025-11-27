<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\grl\GeneralController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'general'], function () {
    Route::post('subir-archivo', [GeneralController::class, 'subirArchivo']);
    Route::post('subir-documento', [GeneralController::class, 'subirDocumento']);
    Route::post('remover-archivo', [GeneralController::class, 'removerArchivo']);
    Route::post('subir-svg-pizarra', [GeneralController::class, 'subirSvgPizarra']);
});

Route::group(['prefix' => 'file'], function () {
    Route::post('descargar', [FileController::class, 'descargarArchivo']);
    Route::get('import', [FileController::class, 'downloadFile']);
    Route::post('validatedDocentes', [FileController::class, 'validatedDocentes']);
    Route::post('validatedEstudiantes', [FileController::class, 'validatedDocentes']);
});
