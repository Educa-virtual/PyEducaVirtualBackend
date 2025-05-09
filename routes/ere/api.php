<?php

use App\Http\Controllers\ere\AlternativasController;
use App\Http\Controllers\ere\AreasController;
use App\Http\Controllers\ere\DesempenosController;
use App\Http\Controllers\ere\EncabezadoPreguntasController;
use App\Http\Controllers\ere\EspecialistasDremoController;
use App\Http\Controllers\ere\EvaluacionController;
use App\Http\Controllers\ere\EvaluacionesController;
use App\Http\Controllers\Ere\ImportarResultadosController;
use App\Http\Controllers\Ere\NivelLogrosController;
use App\Http\Controllers\ere\PreguntasController;
use App\Http\Controllers\Ere\ReporteEvaluacionesController;
use App\Http\Controllers\ere\ResultadosController;
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
            Route::get('nivel-logros', [NivelLogrosController::class, 'obtenerNivelLogrosPorCurso']);
            Route::post('nivel-logros', [NivelLogrosController::class, 'registrarNivelLogroPorCurso']);
        });
        Route::group(['prefix' => 'instituciones-educativas/{iieeId}/directores/{iPersId}'], function () {
            Route::get('areas/horas', [AreasController::class, 'obtenerHorasAreasPorEvaluacionDirectorIe']);
            Route::post('areas/horas', [AreasController::class, 'registrarHorasAreasPorEvaluacionDirectorIe']);
        });
        Route::patch('areas/estado', [AreasController::class, 'actualizarLiberacionAreasPorEvaluacion']);
    });

    Route::get('nivel-logros', [NivelLogrosController::class, 'obtenerNivelLogros']);

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
        Route::delete('simples', [PreguntasController::class, 'eliminarPreguntaSimple']);
        Route::delete('multiples', [PreguntasController::class, 'eliminarPreguntaMultiple']);
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
        Route::post('obtenerEstudianteAreasEvaluacion', [EvaluacionController::class, 'obtenerEstudianteAreasEvaluacion']);
        Route::post('ConsultarPreguntasxiEvaluacionIdxiCursoNivelGradIdxiEstudianteId', [EvaluacionController::class, 'ConsultarPreguntasxiEvaluacionIdxiCursoNivelGradIdxiEstudianteId']);
        Route::post('verificacionInicioxiEvaluacionIdxiCursoNivelGradIdxiIieeId', [EvaluacionController::class, 'verificacionInicioxiEvaluacionIdxiCursoNivelGradIdxiIieeId']);
        Route::post('obtenerEvaluacionxiEvaluacionIdxiCursoNivelGradIdxiIieeId', [EvaluacionController::class, 'obtenerEvaluacionxiEvaluacionIdxiCursoNivelGradIdxiIieeId']);
    });
    Route::group(['prefix' => 'alternativas'], function () {
        Route::post('handleCrudOperation', [AlternativasController::class, 'handleCrudOperation']);
    });
    Route::group(['prefix' => 'encabezado-preguntas'], function () {
        Route::post('handleCrudOperation', [EncabezadoPreguntasController::class, 'handleCrudOperation']);
    });
    Route::group(['prefix' => 'resultados'], function () {
        Route::post('guardarResultadosxiEstudianteIdxiResultadoRptaEstudiante', [ResultadosController::class, 'guardarResultadosxiEstudianteIdxiResultadoRptaEstudiante']);
        Route::post('terminarExamenxiEstudianteId', [ResultadosController::class, 'terminarExamenxiEstudianteId']);
        Route::post('guardarRespuestas', [ResultadosController::class, 'guardarRespuestas']);
    });

    Route::group(['prefix' => 'reportes'], function () {
        // Obtener reporte de resultados de evaluaciones
        Route::post('obtenerEvaluacionesCursosIes', [ReporteEvaluacionesController::class, 'obtenerEvaluacionesCursosIes']);
        Route::post('obtenerInformeResumen', [ReporteEvaluacionesController::class, 'obtenerInformeResumen']);
        Route::post('generarPdf', [ReporteEvaluacionesController::class, 'generarPdf']);
        Route::post('generarExcel', [ReporteEvaluacionesController::class, 'generarExcel']);
        Route::post('importarResultados', [ImportarResultadosController::class, 'importar']);
    });

    /*Route::group(['prefix' => 'nivel-logros'], function () {
        Route::get('', [NivelLogrosController::class, 'obtenerNivelLogros']);
        Route::group(['prefix' => 'evaluaciones/{evaluacionId}'], function () {
            Route::get('cursos/{cursoId}', [NivelLogrosController::class, 'obtenerNivelLogrosPorCurso']);
            Route::post('cursos/{cursoId}', [NivelLogrosController::class, 'registrarNivelLogro']);
        });
    });*/
});
