<?php

use App\Http\Controllers\doc\PortafoliosController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'doc'], function () {
    Route::group(['prefix' => 'portafolio'], function () {
        Route::post('guardar_cuaderno', [PortafoliosController::class, 'guardarCuadernoCampo']);
    });
});