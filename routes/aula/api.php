<?php

use App\Http\Controllers\aula\AulaVirtualController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'aula-virtual'], function () {
    Route::group(['prefix' => 'contenidos'], function () {
        Route::group(['prefix' => 'actividad'], function () {
            Route::post('guardarActividad', [AulaVirtualController::class, 'guardarActividad']);
        });

        Route::get('contenidoSemanasProgramacionActividades', [AulaVirtualController::class, 'contenidoSemanasProgramacionActividades']);
    });
});