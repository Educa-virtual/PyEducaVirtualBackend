<?php

use App\Http\Controllers\Evaluaciones\BancoPreguntasController;
use App\Http\Controllers\Evaluaciones\TipoPreguntaController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'evaluaciones',], function () {
    Route::group(['prefix' => 'tipo-preguntas'], function () {
        Route::get('obtenerTipoPreguntas', [TipoPreguntaController::class, 'obtenerTipoPreguntas']);
    });

    Route::group(['prefix' => 'banco-preguntas'], function () {
        Route::post('guardarActualizarPreguntaConAlternativas', [BancoPreguntasController::class, 'guardarActualizarPreguntaConAlternativas']);
        Route::get('obtenerBancoPreguntas', [BancoPreguntasController::class, 'obtenerBancoPreguntas']);
        Route::get('obtenerEncabezadosPreguntas', [BancoPreguntasController::class, 'obtenerEncabezadosPreguntas']);
    });
});
