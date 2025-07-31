<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\grl\GeneralController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'general'], function () {
    Route::post('subir-archivo', [GeneralController::class, 'subirArchivo']);
    Route::post('remover-archivo', [GeneralController::class, 'removerArchivo']);
});

Route::group(['prefix' => 'file'], function () {
    Route::get('import', [FileController::class, 'downloadFile']);
    Route::post('validatedDocentes', [FileController::class, 'validatedDocentes']);
    Route::post('validatedEstudiantes', [FileController::class, 'validatedDocentes']);
});
