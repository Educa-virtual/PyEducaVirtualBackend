<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\grl\GeneralController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'general'], function () {
    Route::post('subir-archivo', [GeneralController::class, 'subirArchivo']);
});

Route::group(['prefix' => 'file'], function () {
    Route::get('import', [FileController::class, 'downloadFile']);
});
