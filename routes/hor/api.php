<?php

use App\Http\Controllers\hor\HorarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RefreshToken;

Route::group(['prefix' => 'hor', 'middleware' => ['auth:api', RefreshToken::class]], function () {
    Route::group(['prefix' => 'horarios/anio/{iYAcadId}'], function () {
        Route::get('', [HorarioController::class, 'obtenerHorario']);
    });
});
