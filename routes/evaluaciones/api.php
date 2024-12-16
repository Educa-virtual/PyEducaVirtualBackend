<?php

use App\Http\Controllers\Evaluaciones\BancoPreguntasController;
use App\Http\Controllers\Evaluaciones\EscalaCalificacionesController;
use App\Http\Controllers\Evaluaciones\EvaluacionController;
use App\Http\Controllers\Evaluaciones\EvaluacionEstudiantesController;
use App\Http\Controllers\evaluaciones\InstrumentosEvaluacionController;
use App\Http\Controllers\Evaluaciones\LogrosController;
use App\Http\Controllers\Evaluaciones\TipoEvaluacionController as EvaluacionesTipoEvaluacionController;
use App\Http\Controllers\Evaluaciones\TipoPreguntaController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'evaluaciones',], function () {
    Route::group(['prefix' => 'tipo-preguntas'], function () {
        Route::get('obtenerTipoPreguntas', [TipoPreguntaController::class, 'obtenerTipoPreguntas']);
    });

    Route::resource('tipo-evaluaciones', EvaluacionesTipoEvaluacionController::class);

    Route::group(['prefix' => 'banco-preguntas'], function () {
        Route::post('guardarActualizarPreguntaConAlternativas', [BancoPreguntasController::class, 'guardarActualizarPreguntaConAlternativas']);
        Route::get('obtenerBancoPreguntas', [BancoPreguntasController::class, 'obtenerBancoPreguntas']);
        Route::get('obtenerEncabezadosPreguntas', [BancoPreguntasController::class, 'obtenerEncabezadosPreguntas']);
        Route::delete('eliminarBancoPreguntasById/{id}', [BancoPreguntasController::class, 'eliminarBancoPreguntasById']);
    });

    Route::group(['prefix' => 'evaluacion'], function () {
        Route::post('guardarActualizarEvaluacion', [EvaluacionController::class, 'guardarActualizarEvaluacion']);
        Route::post('guardarActualizarPreguntasEvaluacion', [EvaluacionController::class, 'guardarActualizarPreguntasEvaluacion']);
        Route::delete('eliminarPreguntaEvulacion/{id}', [EvaluacionController::class, 'eliminarPreguntaEvulacion']);
        Route::resource('logros', LogrosController::class);
        Route::post('publicar', [EvaluacionController::class, 'publicarEvaluacion']);
        Route::post('anular-publicacion', [EvaluacionController::class, 'anularPublicacionEvaluacion']);

        Route::group(['prefix' => 'estudiantes'], function () {
            Route::resource('', EvaluacionEstudiantesController::class);
            Route::get('obtenerEvaluacionRespuestasEstudiante', [EvaluacionEstudiantesController::class, 'obtenerEvaluacionRespuestasEstudiante']);
            Route::post('calificarLogros', [EvaluacionEstudiantesController::class, 'calificarLogros']);
            Route::post('calificarLogrosRubrica', [EvaluacionEstudiantesController::class, 'calificarLogrosRubrica']);
            Route::post('guardarRespuestaxiEstudianteId', [EvaluacionEstudiantesController::class, 'guardarRespuestaxiEstudianteId']);
        });
    });

    Route::group(['prefix' => 'instrumento-evaluaciones'], function () {
        Route::resource('rubrica', InstrumentosEvaluacionController::class);
        Route::get('obtenerRubricas', [InstrumentosEvaluacionController::class, 'obtenerRubricas']);
        Route::get('obtenerRubrica', [InstrumentosEvaluacionController::class, 'obtenerRubrica']);
    });

    Route::resource('escala-calificaciones', EscalaCalificacionesController::class);

    Route::group(['prefix' => 'escala-calificaciones'], function () {
        Route::post('list', [EscalaCalificacionesController::class, 'list']);
    });
});
