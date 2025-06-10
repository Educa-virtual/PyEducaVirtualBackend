
<?php

use App\Http\Controllers\acad\BuzonSugerenciaController;
use App\Http\Controllers\acad\CursosController;
use App\Http\Controllers\acad\DocenteCursosController;
use App\Http\Controllers\acad\EstudiantesController;
use App\Http\Controllers\acad\GradosController;
use App\Http\Controllers\acad\InstitucionEducativaController;
use App\Http\Controllers\acad\SilabosController;
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
            Route::post('', [BuzonSugerenciaController::class, 'registrarSugerencia']);
            Route::get('', [BuzonSugerenciaController::class, 'obtenerListaSugerenciasEstudiante']);
            Route::group(['prefix' => '{iSugerenciaId}'], function () {
                Route::delete('', [BuzonSugerenciaController::class, 'eliminarSugerencia']);
                Route::get('archivos', [BuzonSugerenciaController::class, 'obtenerArchivosSugerencia']);
                Route::get('archivos/{nombreArchivo}', [BuzonSugerenciaController::class, 'descargarArchivosSugerencia']);
            });
        });

        Route::post('obtenerCursosXEstudianteAnioSemestre', [EstudiantesController::class, 'obtenerCursosXEstudianteAnioSemestre']);
    });

    Route::group(['prefix' => 'directores'], function () {
        Route::get('buzon-sugerencias', [BuzonSugerenciaController::class, 'obtenerListaSugerenciasDirector']);
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
});
