<?php

use App\Http\Controllers\ere\AlternativasController;
use App\Http\Controllers\ere\AreasController;
use App\Http\Controllers\ere\DesempenosController;
use App\Http\Controllers\ere\EncabezadoPreguntasController;
use App\Http\Controllers\ere\EspecialistasDremoController;
use App\Http\Controllers\ere\EvaluacionController;
use App\Http\Controllers\ere\EvaluacionesController;
use App\Http\Controllers\ere\PreguntasController;
use App\Http\Controllers\evaluaciones\AlternativaPreguntaController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'ere'], function () {
    Route::get('evaluaciones/anios', [EvaluacionesController::class, 'obtenerAniosEvaluaciones']);
    Route::group(['prefix' => 'evaluaciones/{evaluacionId}'], function () {
        Route::get('', [EvaluacionesController::class, 'obtenerEvaluacion']);
        Route::get('especialistas/{personaId}/perfiles/{perfilId}/areas', [EspecialistasDremoController::class, 'obtenerAreasPorEvaluacionyEspecialista']);
        Route::group(['prefix' => 'areas/{areaId}'], function () {
            Route::get('preguntas-reutilizables', [PreguntasController::class, 'obtenerPreguntasReutilizables']);
            Route::post('preguntas-reutilizables', [PreguntasController::class, 'asignarPreguntaAEvaluacion']);
            Route::post('archivo-preguntas', [AreasController::class, 'guardarArchivoPdf']);
            Route::get('archivo-preguntas', [AreasController::class, 'descargarArchivoPreguntas']);
            Route::get('matriz-competencias', [AreasController::class, 'generarMatrizCompetencias']);
        });
        Route::patch('areas/estado', [AreasController::class, 'actualizarLiberacionAreasPorEvaluacion']);
    });
<<<<<<< HEAD
    
=======

>>>>>>> 54ce067bf3526bd5fa470cfd18b04f0cfd621e64
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
        Route::post('handleCrudOperation', [PreguntasController::class, 'handleCrudOperation']);
    });

    Route::group(['prefix' => 'encabezado-preguntas'], function () {
        Route::post('guardarActualizarEncabezadoPregunta', [PreguntasController::class, 'guardarActualizarEncabezadoPregunta']);
        Route::delete('eliminarEncabezadoPreguntaById/{id}', [PreguntasController::class, 'eliminarEncabezadoPreguntaById']);
    });
    Route::group(['prefix' => 'desempenos'], function () {
        Route::post('handleCrudOperation', [DesempenosController::class, 'handleCrudOperation']);
    });
    Route::group(['prefix' => 'evaluacion'], function () {
        Route::post('handleCrudOperation', [EvaluacionController::class, 'handleCrudOperation']);
    });
    Route::group(['prefix' => 'alternativas'], function () {
        Route::post('handleCrudOperation', [AlternativasController::class, 'handleCrudOperation']);
    });
    Route::group(['prefix' => 'encabezado-preguntas'], function () {
        Route::post('handleCrudOperation', [EncabezadoPreguntasController::class, 'handleCrudOperation']);
    });
});
