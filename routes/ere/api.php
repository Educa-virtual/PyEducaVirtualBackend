<?php

use App\Http\Controllers\Ere\EvaluacionesController;
use App\Http\Controllers\Ere\PreguntasController;
use App\Http\Controllers\ere\PruebaController;
use App\Http\Controllers\Evaluaciones\AlternativaPreguntaController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'ere'], function () {


    Route::group(['prefix' => 'evaluaciones'], function () {
        Route::get('obtenerAreasPorEvaluacionyEspecialista', [EvaluacionesController::class, 'obtenerAreasPorEvaluacionyEspecialista']);
        Route::get('obtenerEvaluacion', [EvaluacionesController::class, 'obtenerEvaluacion']);
        Route::get('{iEvaluacionId}/areas/{areaId}/exportar-preguntas', [EvaluacionesController::class, 'exportarPreguntasPorArea']);
    });

    Route::group(['prefix' => 'alternativas'], function () {
        Route::post('guardarActualizarAlternativa', [AlternativaPreguntaController::class, 'guardarActualizarAlternativa']);
        Route::get('obtenerAlternativaByPreguntaId/{id}', [AlternativaPreguntaController::class, 'obtenerAlternativaByPreguntaId']);
        Route::delete('eliminarAlternativaById/{id}', [AlternativaPreguntaController::class, 'eliminarAlternativaById']);
    });

    Route::group(['prefix' => 'preguntas'], function () {
        Route::post('guardarActualizarPreguntaConAlternativas', [PreguntasController::class, 'guardarActualizarPreguntaConAlternativas']);
        Route::delete('eliminarBancoPreguntasById/{id}', [PreguntasController::class, 'eliminarBancoPreguntasById']);
        Route::get('obtenerBancoPreguntas', [PreguntasController::class, 'obtenerBancoPreguntas']);
        Route::get('obtenerEncabezadosPreguntas', [PreguntasController::class, 'obtenerEncabezadosPreguntas']);
        //Route::get('exportar-word', [PreguntasController::class, 'exportar-word']);
        Route::patch('actualizarMatrizPreguntas', [PreguntasController::class, 'actualizarMatrizPreguntas']);
    });

    Route::group(['prefix' => 'encabezado-preguntas'], function () {
        Route::post('guardarActualizarEncabezadoPregunta', [PreguntasController::class, 'guardarActualizarEncabezadoPregunta']);
        Route::delete('eliminarEncabezadoPreguntaById/{id}', [PreguntasController::class, 'eliminarEncabezadoPreguntaById']);
    });
});
