<?php

use App\Http\Controllers\ere\AlternativasController;
use App\Http\Controllers\ere\AreasController;
use App\Http\Controllers\ere\CapacidadesController;
use App\Http\Controllers\ere\CompetenciasController;
use App\Http\Controllers\ere\cursoController;
use App\Http\Controllers\ere\DesempenosController;
use App\Http\Controllers\ere\EncabezadoPreguntasController;
use App\Http\Controllers\ere\EspecialistasDremoController;
use App\Http\Controllers\ere\EvaluacionController;
use App\Http\Controllers\ere\EvaluacionesController;
use App\Http\Controllers\ere\EvaluacionExclusionesController;
use App\Http\Controllers\ere\InstitucionesEducativasController;
use App\Http\Controllers\ere\NivelEvaluacionController;
use App\Http\Controllers\Ere\ImportarResultadosController;
use App\Http\Controllers\Ere\NivelLogrosController;
use App\Http\Controllers\ere\NivelTipoController;
use App\Http\Controllers\ere\PreguntasController;
use App\Http\Controllers\Ere\ReporteEvaluacionesController;
use App\Http\Controllers\ere\ResultadosController;
use App\Http\Controllers\ere\TipoEvaluacionController;
use App\Http\Controllers\ere\UgelesController;
use App\Http\Controllers\evaluaciones\AlternativaPreguntaController;
use App\Http\Middleware\RefreshToken;
use Illuminate\Support\Facades\Route;
//$this->middleware('auth:api');
Route::group(['prefix' => 'ere', 'middleware' => ['auth:api', RefreshToken::class]], function () {
    Route::get('evaluaciones/anios', [EvaluacionesController::class, 'obtenerAniosEvaluaciones']);
    Route::group(['prefix' => 'evaluaciones/{evaluacionId}'], function () {
        Route::get('', [EvaluacionesController::class, 'obtenerEvaluacion']);
        Route::get('estudiante/areas', [AreasController::class, 'obtenerAreasPorEvaluacionEstudiante']);
        Route::get('estudiante/areas/{iAreaId}/resultado', [EvaluacionController::class, 'obtenerResultadoEvaluacionEstudiante']);
        //Route::get('especialistas/{personaId}/perfiles/{perfilId}/areas', [EspecialistasDremoController::class, 'obtenerAreasPorEvaluacionyEspecialista']);
        Route::get('areas', [AreasController::class, 'obtenerAreasPorEvaluacion']);
        Route::patch('areas/estado', [AreasController::class, 'actualizarLiberacionAreasPorEvaluacion']);
        Route::group(['prefix' => 'areas/{areaId}'], function () {
            //Route::get('descargas/estado', [AreasController::class, 'obtenerEstadoDescarga']);
            Route::patch('descargas/estado', [AreasController::class, 'actualizarEstadoDescarga']);
            Route::get('preguntas-reutilizables', [PreguntasController::class, 'obtenerPreguntasReutilizables']);
            Route::post('preguntas-reutilizables', [PreguntasController::class, 'asignarPreguntaAEvaluacion']);
            Route::post('archivo-preguntas', [AreasController::class, 'guardarArchivoPdf']);
            Route::get('archivo-preguntas', [AreasController::class, 'descargarArchivoPreguntas']);
            Route::delete('archivo-preguntas', [AreasController::class, 'eliminarArchivoPreguntasPdf']);
            Route::get('matriz-competencias', [AreasController::class, 'generarMatrizCompetencias']);
            Route::get('cartilla-respuestas', [AreasController::class, 'descargarCartillaRespuestas']);
            Route::get('nivel-logros', [NivelLogrosController::class, 'obtenerNivelLogrosPorCurso']);
            Route::post('nivel-logros', [NivelLogrosController::class, 'registrarNivelLogroPorCurso']);
            Route::get('cantidad-maxima-preguntas', [EvaluacionController::class, 'obtenerCantidadMaximaPreguntas']);
        });
        Route::group(['prefix' => 'instituciones-educativas/{iieeId}/directores/{iPersId}'], function () {
            Route::get('areas/horas', [AreasController::class, 'obtenerHorasAreasPorEvaluacionDirectorIe']);
            Route::post('areas/horas', [AreasController::class, 'registrarHorasAreasPorEvaluacionDirectorIe']);
        });
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
        Route::post('obtenerInformeComparacion', [ReporteEvaluacionesController::class, 'obtenerInformeComparacion']);
        Route::post('generarPdfComparacion', [ReporteEvaluacionesController::class, 'obtenerInformeComparacionPdf']);
        Route::post('generarExcelComparacion', [ReporteEvaluacionesController::class, 'obtenerInformeComparacionExcel']);
        Route::post('importarOffLine', [ImportarResultadosController::class, 'importarOffLine']);
    });

    Route::group(['prefix' => 'ie'], function () {
        Route::get('obtenerIE', [InstitucionesEducativasController::class, 'obtenerInstitucionesEducativas']);
    });
    Route::group(['prefix' => 'nivelTipo'], function () {
        Route::get('obtenerNivelTipo', [NivelTipoController::class, 'obtenerNivelTipo']);
    });

    Route::group(['prefix' => 'tipoEvaluacion'], function () {
        Route::get('obtenerTipoEvaluacion', [TipoEvaluacionController::class, 'obtenerTipoEvaluacion']);
    });

    Route::group(['prefix' => 'nivelEvaluacion'], function () {
        Route::get('obtenerNivelEvaluacion', [NivelEvaluacionController::class, 'obtenerNivelEvaluacion']);
    });

    Route::group(['prefix' => 'competencias'], function () {
        Route::get('obtenerCompetencias', [CompetenciasController::class, 'obtenerCompetencias']);
    });

    Route::group(['prefix' => 'capacidades'], function () {
        Route::get('obtenerCapacidades', [CapacidadesController::class, 'obtenerCapacidades']);
    });


    Route::group(['prefix' => 'desempenos'], function () {
        Route::get('obtenerDesempenos', [DesempenosController::class, 'obtenerDesempenos']);
    });

    Route::group(['prefix' => 'curso'], function () {
        Route::get('obtenerCursos', [cursoController::class, 'obtenerCursos']);
    });
    Route::group(['prefix' => 'Evaluaciones'], function () {
        Route::get('estudiante', [EvaluacionController::class, 'obtenerEvaluacionesPorEstudiante']);
        Route::get('ereObtenerEvaluacion', [EvaluacionesController::class, 'obtenerEvaluaciones']); // Cambié el nombre de la ruta para que sea más limpio

        Route::get('obtenerUltimaEvaluacion', [EvaluacionesController::class, 'obtenerUltimaEvaluacion']);
        Route::post('guardar', [EvaluacionesController::class, 'guardarEvaluacion']);
        Route::post('actualizar', [EvaluacionesController::class, 'actualizarEvaluacion']);
        //Agregando participacion y eliminando participacion, IE
        Route::post('guardarParticipacion', [EvaluacionesController::class, 'guardarParticipacion']);
        Route::delete('eliminarParticipacion', [EvaluacionesController::class, 'eliminarParticipacion']);
        //Agregando participacion nuevo
        Route::post('guardarParticipacionNuevo', [EvaluacionesController::class, 'guardarParticipacionNuevo']);
        // Ruta para actualizar la evaluación
        Route::put('actualizar/{iEvaluacionId}', [EvaluacionesController::class, 'actualizarEvaluacion']);
        // Ruta para obtener las participaciones
        //Route::get('obtenerParticipaciones', [EvaluacionesController::class, 'obtenerParticipaciones']);
        Route::get('obtenerParticipaciones/{iEvaluacionId}', [EvaluacionesController::class, 'obtenerParticipaciones']);
        //Nuevo Ver con Datos completos
        Route::get('verParticipacionNuevo', [EvaluacionesController::class, 'verParticipacionNuevo']);
        //Obtener Cursos
        Route::post('obtenerCursos', [EvaluacionesController::class, 'obtenerCursos']);
        //Insertar Cursos
        Route::post('insertarCursos', [EvaluacionesController::class, 'insertarCursos']);
        //Eliminar Cursos
        Route::delete('eliminarCursos', [EvaluacionesController::class, 'eliminarCursos']);
        //Ver Cursos
        Route::get('evaluaciones/{iEvaluacionId}/cursos', [EvaluacionesController::class, 'obtenerCursosEvaluacion']);
        //Actualizar Cursos COMENTADO
        Route::post('evaluaciones/{iEvaluacionId}/actualizarCursos', [EvaluacionesController::class, 'actualizarCursosEvaluacion']);
        //Obtener Evaluacion Copiar
        Route::get('/obtenerEvaluacionCopia', [EvaluacionesController::class, 'obtenerEvaluacionCopia']);
        //Obtener evaluacion Copiar 2
        Route::get('/obtenerEvaluacionCopia2', [EvaluacionesController::class, 'obtenerEvaluacionCopia2']);
        // ACTUALIZAAR En routes/api.php o routes/web.php
        Route::put('actualizarCursos', [EvaluacionesController::class, 'actualizarCursos']);
        //Agregando CopiarEvaluacion
        Route::post('copiarEvaluacion', [EvaluacionesController::class, 'copiarEvaluacion']);
        //ObtenerMatrizCompetencia
        Route::get('obtenerMatrizCompetencias', [EvaluacionesController::class, 'obtenerMatrizCompetencias']);
        //ObtenerMatrizCapacidad
        Route::get('obtenerMatrizCapacidades', [EvaluacionesController::class, 'obtenerMatrizCapacidades']);
        //InsertarMatrizDesempeno
        Route::post('insertarMatrizDesempeno', [EvaluacionesController::class, 'insertarMatrizDesempeno']);
        //ObtenerEspecialistas
        Route::get('obtenerEspDrem', [EvaluacionesController::class, 'obtenerEspDrem']);
        //Obtener por el iGradoId los Cursos del Especialista
        Route::get('obtenerEspDremCurso', [EvaluacionesController::class, 'obtenerEspDremCurso']);
        //Matriz Descargar
        Route::get('generarPdfMatrizbyEvaluacionId', [EvaluacionesController::class, 'generarPdfMatrizbyEvaluacionId']);
        //Insertar pregunta seleccionada
        Route::post('insertarPreguntaSeleccionada', [EvaluacionesController::class, 'insertarPreguntaSeleccionada']);
        //Obtener pregunta seleccionada
        Route::get('obtenerPreguntaSeleccionada', [EvaluacionesController::class, 'obtenerPreguntaSeleccionada']);
        //Obtener preguntas por EvaluacionId y preguntaId
        Route::get('obtenerPreguntaInformacion', [EvaluacionesController::class, 'obtenerPreguntaInformacion']);
        //Obtener conteo por curso
        Route::post('obtenerConteoPorCurso', [EvaluacionesController::class, 'obtenerConteoPorCurso']);
        //Guardar fecha inicio fin de cursos
        Route::post('guardarInicioFinalExmAreas', [EvaluacionesController::class, 'guardarInicioFinalExmAreas']);
        //Eliminar una pregunta de una evaluación.
        Route::delete('eliminarPregunta', [EvaluacionesController::class, 'eliminarPregunta']);
         //guardar Fecha de Inicio y Cantidad de preguntas en examen cursos
         Route::post('guardarFechaCantidadExamenCursos', [EvaluacionesController::class, 'guardarFechaCantidadExamenCursos']);

        // Gestionar exclusion de estudiantes en evaluaciones ERE
        Route::post('listarExclusiones', [EvaluacionExclusionesController::class, 'listarExclusiones']);
        Route::post('guardarExclusion', [EvaluacionExclusionesController::class, 'guardarExclusion']);
        Route::post('actualizarExclusion', [EvaluacionExclusionesController::class, 'actualizarExclusion']);
        Route::post('verExclusion', [EvaluacionExclusionesController::class, 'verExclusion']);
        Route::post('eliminarExclusion', [EvaluacionExclusionesController::class, 'eliminarExclusion']);
    });
    Route::group(['prefix' => 'Ugeles'], function () {
        Route::get('obtenerUgeles', [UgelesController::class, 'obtenerUgeles']);
        Route::post('importarOffLine', [ImportarResultadosController::class, 'importarOffLine']);
    });
    //route periodo/Evaluaciones
    Route::get('evaluaciones/periodos-evaluacion', [App\Http\Controllers\eval\EvaluacionesController::class, 'obtenerPeriodosEvaluacion']);

    /*Route::group(['prefix' => 'nivel-logros'], function () {
        Route::get('', [NivelLogrosController::class, 'obtenerNivelLogros']);
        Route::group(['prefix' => 'evaluaciones/{evaluacionId}'], function () {
            Route::get('cursos/{cursoId}', [NivelLogrosController::class, 'obtenerNivelLogrosPorCurso']);
            Route::post('cursos/{cursoId}', [NivelLogrosController::class, 'registrarNivelLogro']);
        });
    });*/
});
