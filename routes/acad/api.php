
<?php

use App\Http\Controllers\acad\BandejaCotnroller;
use App\Http\Controllers\acad\BuzonSugerenciaDirectorController;
use App\Http\Controllers\acad\BuzonSugerenciaEstudianteController;
use App\Http\Controllers\acad\CalendarioPeriodosEvaluacionesController;
use App\Http\Controllers\acad\ContenidoSemanasController;
use App\Http\Controllers\acad\CursosController;
use App\Http\Controllers\acad\DetalleMatriculasController;
use App\Http\Controllers\acad\DocenteCursosController;
use App\Http\Controllers\acad\EstudiantesController;
use App\Http\Controllers\acad\GradosController;
use App\Http\Controllers\acad\InstitucionEducativaController;
use App\Http\Controllers\acad\PeriodoEvaluacionesController;
use App\Http\Controllers\acad\SilabosController;
use App\Http\Controllers\api\acad\DistribucionBloqueController;
use App\Http\Controllers\api\acad\FeriadoImportanteController;
use App\Http\Controllers\api\acad\TipoFechaController;
use App\Http\Controllers\asi\AsistenciaController;
use App\Http\Controllers\VacantesController;
use App\Http\Controllers\ere\EspecialistasDremoController;
use App\Http\Controllers\ere\EspecialistasUgelController;
use App\Http\Controllers\ere\UgelesController;
use App\Http\Middleware\RefreshToken;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'acad', 'middleware' => ['auth:api', RefreshToken::class]], function () {

    Route::group(['prefix' => 'instituciones-educativas'], function () {
        Route::get('', [InstitucionEducativaController::class, 'obtenerInstitucionesEducativas']);
        Route::get('{iIieeId}/sedes', [InstitucionEducativaController::class, 'obtenerSedesIe']);
    });

    Route::group(['prefix' => 'estudiantes'], function () {
        Route::group(['prefix' => 'buzon-sugerencias'], function () {
            Route::post('', [BuzonSugerenciaEstudianteController::class, 'registrarSugerencia']);
            Route::get('', [BuzonSugerenciaEstudianteController::class, 'obtenerListaSugerencias']);
            Route::group(['prefix' => '{iSugerenciaId}'], function () {
                Route::delete('', [BuzonSugerenciaEstudianteController::class, 'eliminarSugerencia']);
                Route::get('archivos', [BuzonSugerenciaEstudianteController::class, 'obtenerArchivosSugerencia']);
                Route::get('archivos/{nombreArchivo}', [BuzonSugerenciaEstudianteController::class, 'descargarArchivosSugerencia']);
                //Route::get('', [BuzonSugerenciaEstudianteController::class, 'obtenerListaSugerenciasEstudiante']);
                //Route::get('{id}', [BuzonSugerenciaEstudianteController::class, 'obtenerListaSugerenciaConRespuesta']);
                //Route::post('', [BuzonSugerenciaEstudianteController::class, 'registrarSugerencia']);
            });
        });
        Route::post('obtenerCursosXEstudianteAnioSemestre', [EstudiantesController::class, 'obtenerCursosXEstudianteAnioSemestre']);
    });

    Route::group(['prefix' => 'directores'], function () {
        Route::group(['prefix' => 'buzon-sugerencias'], function () {
            Route::get('', [BuzonSugerenciaDirectorController::class, 'obtenerListaSugerencias']);
            Route::post('', [BuzonSugerenciaDirectorController::class, 'registrarRespuestaSugerencias']);
        });
        //Route::get('{iSugerenciaId}/detalle', [BuzonSugerenciaController:: class, 'obtenerDetalleSugerencia']);
        //Route::get('{iSugerenciaId}/responder', [BuzonSugerenciaController:: class, 'rsponderSugerencia']);
    });
});

Route::group(['prefix' => 'acad'], function () {
    Route::group(['prefix' => 'vacantes'], function () {
        Route::post('guardar', [VacantesController::class, 'guardarVacantes']);
        //vacantes convenciones de nombre para APIs
    });

    Route::group(['prefix' => 'grados'], function () {
        Route::post('handleCrudOperation', [GradosController::class, 'handleCrudOperation']);
    });

    Route::group(['prefix' => 'especialistas-dremo'], function () {
        Route::get('', [EspecialistasDremoController::class, 'obtenerEspecialistas']);
        Route::get('{docenteId}/areas', [EspecialistasDremoController::class, 'obtenerAreasPorEspecialista']);
        Route::post('{docenteId}/areas', [EspecialistasDremoController::class, 'asignarAreaEspecialista']);
        Route::delete('{docenteId}/areas', [EspecialistasDremoController::class, 'eliminarAreaEspecialista']);
    });

    Route::group(['prefix' => 'especialistas-ugel'], function () {
        Route::get('', [EspecialistasUgelController::class, 'obtenerEspecialistas']);
    });

    Route::group(['prefix' => 'ugeles'], function () {
        Route::get('', [UgelesController::class, 'obtenerUgelesIdCifrado']);
        Route::get('{ugelId}/especialistas/{docenteId}/areas', [EspecialistasUgelController::class, 'obtenerAreasPorEspecialista']);
        Route::post('{ugelId}/especialistas/{docenteId}/areas', [EspecialistasUgelController::class, 'asignarAreaEspecialista']);
        Route::delete('{ugelId}/especialistas/{docenteId}/areas', [EspecialistasUgelController::class, 'eliminarAreaEspecialista']);
    });

    Route::group(['prefix' => 'silabos'], function () {
        Route::post('actualizar', [SilabosController::class, 'actualizar']);
    });

    Route::group(['prefix' => 'cursos'], function () {
        Route::get('', [CursosController::class, 'listarCursosPorNivel']);
    });

    // Muestra los docentes con sus cursos
    Route::group(['prefix' => 'docente'], function () {
        Route::post('docente_curso', [DocenteCursosController::class, 'buscarDocenteCurso']);
        Route::post('importar_silabos', [DocenteCursosController::class, 'importarSilabos']);
        Route::post('detalle_curricular', [AsistenciaController::class, 'obtenerDetallesCurricular']);
    });
    Route::group(['prefix' => 'tipos-fechas'], function () {
        Route::get('getTiposFechas', [TipoFechaController::class, 'getTiposFechas']);
    });

    Route::group(['prefix' => 'feriados-importantes'], function () {
        Route::get('getFechasImportantes/{iYAcadId?}/{iSedeId?}', [FeriadoImportanteController::class, 'getFechasImportantes']);
        Route::get('getDependenciaFechas/{iFechaImpId?}', [FeriadoImportanteController::class, 'getDependenciaFechas']);
        Route::post('insFechasImportantes', [FeriadoImportanteController::class, 'insFechasImportantes']);
        Route::put('updFechasImportantes', [FeriadoImportanteController::class, 'updFechasImportantes']);
        Route::delete('deleteFechasImportantes/{iFechaImpId}', [FeriadoImportanteController::class, 'deleteFechasImportantes']);
    });

    Route::group(['prefix' => 'distribucion-bloques'], function () {
        Route::get('getDistribucionBloques/{iYearId}/{iDistribucionBloqueId?}', [DistribucionBloqueController::class, 'getDistribucionBloques']);
        Route::post('insDistribucionBloques', [DistribucionBloqueController::class, 'insDistribucionBloques']);
        Route::put('updDistribucionBloques', [DistribucionBloqueController::class, 'updDistribucionBloques']);
        Route::delete('deleteDistribucionBloques/{iDistribucionBloqueId}', [DistribucionBloqueController::class, 'deleteDistribucionBloques']);
    });

    Route::group(['prefix' => 'periodo-evaluaciones'], function () {
        Route::get('getPeriodoEvaluaciones/{iYearId?}', [PeriodoEvaluacionesController::class, 'getPeriodoEvaluaciones']);
        Route::post('processConfigCalendario', [PeriodoEvaluacionesController::class, 'processConfigCalendario']);
    });
    
    Route::prefix('detalle-matriculas')->group(function () {
        Route::put('/{iDetMatrId}', [DetalleMatriculasController::class, 'guardarConclusionDescriptiva']); // Para actualizar
    });

    Route::group(['prefix' => 'calendario-periodos-evaluaciones'], function () {
        Route::get('/{iYAcadId}/sede/{iSedeId}', [CalendarioPeriodosEvaluacionesController::class, 'obtenerPeriodosxiYAcadIdxiSedeIdxFaseRegular']);
    });
    Route::group(['prefix' => 'contenido-semanas'], function () {
        Route::post('', [ContenidoSemanasController::class, 'guardarContenidoSemanas']);
        Route::put('/{iContenidoSemId}', [ContenidoSemanasController::class, 'actualizarContenidoSemanas']);
        Route::delete('/{iContenidoSemId}', [ContenidoSemanasController::class, 'eliminarContenidoSemanas']);
        Route::get('/{iContenidoSemId}', [ContenidoSemanasController::class, 'obtenerContenidoSemanasxiContenidoSemId']);
        Route::get('/curso/{idDocCursoId}/year/{iYAcadId}', [ContenidoSemanasController::class, 'obtenerContenidoSemanasxidDocCursoIdxiYAcadId']);
        Route::get('/{iContenidoSemId}/actividades', [ContenidoSemanasController::class, 'obtenerActividadesxiContenidoSemId']);
    });

    Route::prefix('bandejaEntrante')->group(function () {
        Route::post('bandeja-estudiante',[BandejaCotnroller::class,'bandejaEstudiante']);
    });

});
