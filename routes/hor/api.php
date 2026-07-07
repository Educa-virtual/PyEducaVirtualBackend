<?php

use App\Http\Controllers\hor\HorarioController;
use App\Http\Middleware\RefreshToken;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'hor', 'middleware' => ['auth:api', RefreshToken::class]], function () {
    Route::group(['prefix' => 'horarios/anio/{iYAcadId}'], function () {
        Route::get('', [HorarioController::class, 'obtenerHorario']);
    });
});
