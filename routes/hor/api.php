<?php

use App\Http\Controllers\hor\HorarioController;
use App\Http\Middleware\RefreshToken;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'hor', 'middleware' => ['auth:api', RefreshToken::class]], function () {
    Route::group(['prefix' => 'horarios/anio/{iYAcadId}'], function () {
        Route::get('', [HorarioController::class, 'obtenerHorario']);
    });

    Route::post('buscarHorario', [HorarioController::class, 'selHorario']);

    Route::group(['prefix' => 'calendarioAcademico'], function () {
        Route::post('guardarHorario', [HorarioController::class, 'insHorario']);
        Route::post('guardarDetalleBloque', [HorarioController::class, 'insDetalleBloque']);
    });
    
});
