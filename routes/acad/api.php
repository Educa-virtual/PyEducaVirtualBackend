<?php

use App\Http\Controllers\acad\CursosController;
use App\Http\Controllers\acad\DocenteCursosController;
use App\Http\Controllers\acad\EstudiantesController;
use App\Http\Controllers\acad\GradosController;
use App\Http\Controllers\acad\SilabosController;
use App\Http\Controllers\asi\AsistenciaController;
use App\Http\Controllers\ere\EspecialistasDremoController;
use App\Http\Controllers\ere\EspecialistasUgelController;
use App\Http\Controllers\ere\UgelesController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'acad'], function () {

    Route::group(['prefix' => 'estudiantes'], function () {
        Route::post('obtenerCursosXEstudianteAnioSemestre', [EstudiantesController::class, 'obtenerCursosXEstudianteAnioSemestre']);
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
