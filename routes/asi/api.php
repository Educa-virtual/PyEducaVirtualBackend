<?php

use App\Http\Controllers\asi\AsistenciaController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'asi'], function () {
    Route::group(['prefix' => 'grupos'], function () {
        Route::post('verificar-grupo-asistencia', [AsistenciaController::class, 'verificarGrupoAsistencia']);
        Route::post('guardar-grupo-asistencia', [AsistenciaController::class, 'guardarGrupo']);  
    });
});
