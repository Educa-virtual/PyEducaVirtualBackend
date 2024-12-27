<?php

use App\Helpers\JsonResponseStrategy;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\eval\EvaluacionesController;
use App\Http\Controllers\eval\BancoAlternativasController;
use App\Http\Controllers\eval\EncabezadoPreguntasController;
use App\Http\Controllers\eval\EvaluacionPreguntasController;
use App\Http\Controllers\eval\EvaluacionRespuestasController;
use App\Http\Controllers\Evaluaciones\BancoPreguntasController;
use App\Http\Controllers\Evaluaciones\EscalaCalificacionesController;
use App\Http\Controllers\Evaluaciones\EvaluacionController;
use App\Http\Controllers\Evaluaciones\EvaluacionEstudiantesController;
use App\Http\Controllers\evaluaciones\InstrumentosEvaluacionController;
use App\Http\Controllers\Evaluaciones\LogrosController;
use App\Http\Controllers\Evaluaciones\TipoEvaluacionController as EvaluacionesTipoEvaluacionController;
use App\Http\Controllers\Evaluaciones\TipoPreguntaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\eval\BancoPreguntasController as EvaluacionesBancoPreguntasController ;
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
        Route::get('obtenerRubricaEvaluacion', [InstrumentosEvaluacionController::class, 'obtenerRubricaEvaluacion']);
    });

    Route::resource('escala-calificaciones', EscalaCalificacionesController::class);

    Route::group(['prefix' => 'escala-calificaciones'], function () {
        Route::post('list', [EscalaCalificacionesController::class, 'list']);
    });

    Route::group(['prefix' => 'banco-alternativas'], function () {
        Route::post('handleCrudOperation', [BancoAlternativasController::class, 'handleCrudOperation']);
    });
    Route::group(['prefix' => 'banco-preguntas'], function () {
        Route::post('handleCrudOperation', [EvaluacionesBancoPreguntasController::class, 'handleCrudOperation']);
    });
    Route::group(['prefix' => 'encabezado-preguntas'], function () {
        Route::post('handleCrudOperation', [EncabezadoPreguntasController::class, 'handleCrudOperation']);
    });
    Route::group(['prefix' => 'evaluaciones'], function () {
        Route::post('handleCrudOperation', [EvaluacionesController::class, 'handleCrudOperation']);
    });
    Route::group(['prefix' => 'evaluacion-preguntas'], function () {
        Route::post('handleCrudOperation', [EvaluacionPreguntasController::class, 'handleCrudOperation']);
    });
    Route::group(['prefix' => 'evaluacion-respuestas'], function () {
        Route::post('handleCrudOperation', [EvaluacionRespuestasController::class, 'handleCrudOperation']);
    });
    Route::group(['prefix' => 'evaluacion-promedios'], function () {
        Route::post('guardarConclusionxiEvalPromId', [EvaluacionesController::class, 'guardarConclusionxiEvalPromId']);
    });
});

Route::group(['prefix' => 'virtual'], function () {
    Route::get('getData', function (Request $request) {
        $strategy = new JsonResponseStrategy(); // Puedes decidir la estrategia aquí
        return (new ApiController)->selDesdeTablaOVista($request, $strategy);
    });
    
    Route::post('insertData', [ApiController::class, 'insEnTablaDesdeJSON']);
    Route::put('updateData', [ApiController::class, 'updEnTablaConJSON']);
    Route::delete('deleteData', [ApiController::class, 'delRegistroConTransaccion']);
});
