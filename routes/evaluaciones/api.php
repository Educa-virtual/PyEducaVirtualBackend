<?php

use App\Helpers\JsonResponseStrategy;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\eval\EvaluacionesController;
use App\Http\Controllers\eval\BancoAlternativasController;
use App\Http\Controllers\eval\EncabezadoPreguntasController;
use App\Http\Controllers\eval\EvaluacionPreguntasController;
use App\Http\Controllers\eval\EvaluacionRespuestasController;
use App\Http\Controllers\evaluaciones\BancoPreguntasController;
use App\Http\Controllers\evaluaciones\EscalaCalificacionesController;
use App\Http\Controllers\evaluaciones\EvaluacionController;
use App\Http\Controllers\evaluaciones\EvaluacionEstudiantesController;
use App\Http\Controllers\evaluaciones\InstrumentosEvaluacionController;
use App\Http\Controllers\evaluaciones\LogrosController;
use App\Http\Controllers\evaluaciones\TipoEvaluacionController as EvaluacionesTipoEvaluacionController;
use App\Http\Controllers\evaluaciones\TipoPreguntaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\eval\BancoPreguntasController as EvaluacionesBancoPreguntasController;
use App\Http\Controllers\eval\EvaluacionPromediosController;
use Illuminate\Http\Request;

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
        Route::get('/{iEvaluacionId}/curso/{iCursoId}/docente/{iDocenteId}', [EvaluacionesBancoPreguntasController::class, 'obtenerBancoPreguntasxiEvaluacionIdxiCursoIdxiDocenteId']); // Para obtener
        Route::post('importar', [EvaluacionesBancoPreguntasController::class, 'importarBancoPreguntas']);
        Route::post('/', [EvaluacionesBancoPreguntasController::class, 'guardarBancoPreguntas']); // Para crear
        Route::put('/{iBancoId}', [EvaluacionesBancoPreguntasController::class, 'actualizarBancoPreguntasxiBancoId']); // Para actualizar
        Route::delete('/{iBancoId}', [EvaluacionesBancoPreguntasController::class, 'eliminarBancoPreguntasxiBancoId']); // Para eliminar

    });

    Route::group(['prefix' => 'evaluacion'], function () {
        Route::post('guardarActualizarEvaluacion', [EvaluacionController::class, 'guardarActualizarEvaluacion']);
        Route::post('guardarActualizarPreguntasEvaluacion', [EvaluacionController::class, 'guardarActualizarPreguntasEvaluacion']);
        Route::delete('eliminarPreguntaEvulacion/{id}', [EvaluacionController::class, 'eliminarPreguntaEvulacion']);
        Route::resource('logros', LogrosController::class);
        Route::post('publicar', [EvaluacionController::class, 'publicarEvaluacion']);
        Route::post('anular-publicacion', [EvaluacionController::class, 'anularPublicacionEvaluacion']);

        Route::post('actualizarRubricaEvaluacion', [EvaluacionController::class, 'actualizarRubricaEvaluacion']);
        Route::post('deleteRubricaEvaluacion', [EvaluacionController::class, 'deleteRubricaEvaluacion']);

        Route::post('guardarActualizarCalificacionRubricaEvaluacion', [EvaluacionController::class, 'guardarActualizarCalificacionRubricaEvaluacion']);

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
        Route::post('obtenerRubricaEvaluacion', [InstrumentosEvaluacionController::class, 'obtenerRubricaEvaluacion']);
    });

    Route::resource('escala-calificaciones', EscalaCalificacionesController::class);

    Route::group(['prefix' => 'escala-calificaciones'], function () {
        Route::get('', [EscalaCalificacionesController::class, 'obtenerEscalaCalificaciones']);
    });

    Route::group(['prefix' => 'banco-alternativas'], function () {
        Route::post('handleCrudOperation', [BancoAlternativasController::class, 'handleCrudOperation']);
    });
    Route::group(['prefix' => 'banco-preguntas'], function () {
        Route::post('handleCrudOperation', [EvaluacionesBancoPreguntasController::class, 'handleCrudOperation']);
    });
    Route::group(['prefix' => 'encabezado-preguntas'], function () {
        Route::post('/', [EncabezadoPreguntasController::class, 'guardarEncabezadoPreguntas']); // Para crear
        Route::get('/{iEvaluacionId}', [EncabezadoPreguntasController::class, 'obtenerEncabezadoPreguntasxiEvaluacionId']); // Para obtener x iEvaluacionId
        Route::put('/{iEvalPregId}', [EncabezadoPreguntasController::class, 'actualizarEncabezadoPreguntasxiEvalPregId']); // Para actualizar x iEvalPregId
        Route::delete('/{iEvalPregId}', [EncabezadoPreguntasController::class, 'eliminarEncabezadoPreguntasxiEvalPregId']); // Para eliminar x iEvalPregId
        Route::post('/banco', [EncabezadoPreguntasController::class, 'guardarBancoEncabezadoPreguntas']); // Para crear
        Route::put('/banco/{idEncabPregId}', [EncabezadoPreguntasController::class, 'actualizarBancoEncabezadoPreguntasxidEncabPregId']); // Para actualizar x idEncabPregId
        Route::delete('/banco/{idEncabPregId}', [EncabezadoPreguntasController::class, 'eliminarBancoEncabezadoPreguntasxidEncabPregId']); // Para eliminar x idEncabPregId
    });
    Route::group(['prefix' => 'evaluaciones'], function () {
        Route::post('handleCrudOperation', [EvaluacionesController::class, 'handleCrudOperation']); //corregir 16/06/2025
        Route::post('/', [EvaluacionesController::class, 'guardarEvaluaciones']); // Para crear
        Route::get('/{iEvaluacionId}', [EvaluacionesController::class, 'obtenerEvaluacionesxiEvaluacionId']); // Para obtener
        Route::put('/{iEvaluacionId}', [EvaluacionesController::class, 'actualizarEvaluacionesxiEvaluacionId']); // Para actualizar
        Route::delete('/{iEvaluacionId}', [EvaluacionesController::class, 'eliminarEvaluacionesxiEvaluacionId']); // Para eliminar
        Route::get('/{iEvaluacionId}/estudiante/{iEstudianteId}', [EvaluacionesController::class, 'obtenerEvaluacionPreguntasxiEvaluacionIdxiEstudianteId']); // Para eliminar
        Route::post('/obtenerReporteEstudiantesRetroalimentacion', [EvaluacionesController::class, 'obtenerReporteEstudiantesRetroalimentacion']);
    });
    Route::group(['prefix' => 'evaluacion-preguntas'], function () {
        Route::post('/', [EvaluacionPreguntasController::class, 'guardarEvaluacionPreguntas']); // Para crear
        Route::get('/{iEvaluacionId}', [EvaluacionPreguntasController::class, 'obtenerEvaluacionPreguntasxiEvaluacionId']); // Para obtener x iEvaluacionId
        Route::put('/{iEvalPregId}', [EvaluacionPreguntasController::class, 'actualizarEvaluacionPreguntasxiEvalPregId']); // Para actualizar x iEvalPregId
        Route::delete('/{iEvalPregId}', [EvaluacionPreguntasController::class, 'eliminarEvaluacionPreguntasxiEvalPregId']); // Para eliminar x iEvalPregId
        Route::get('/{iEvaluacionId}/estudiante/{iEstudianteId}', [EvaluacionPreguntasController::class, 'obtenerEvaluacionPreguntasxiEvaluacionIdxiEstudianteId']); // Para eliminar

    });
    Route::group(['prefix' => 'evaluacion-respuestas'], function () {
        Route::post('handleCrudOperation', [EvaluacionRespuestasController::class, 'handleCrudOperation']);
    });
    Route::group(['prefix' => 'evaluacion-promedios'], function () {
        Route::post('/', [EvaluacionPromediosController::class, 'guardarConclusionxiEvaluacionIdxiEstudianteId']);
    });
});

Route::group(['prefix' => 'virtual'], function () {
    Route::post('getData', [ApiController::class, 'getData']);
    Route::post('insertData', [ApiController::class, 'insertData']);
    Route::post('updateData', [ApiController::class, 'updateData']);
    Route::post('deleteData', [ApiController::class, 'deleteData']);
});
