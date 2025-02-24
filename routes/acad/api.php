<?php

use App\Http\Controllers\acad\CursosController;
use App\Http\Controllers\acad\EstudiantesController;
use App\Http\Controllers\acad\GradosController;
use App\Http\Controllers\Ere\EspecialistasDremoController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'acad'], function () {

    Route::group(['prefix' => 'estudiantes'], function () {
        Route::post('obtenerCursosXEstudianteAnioSemestre', [EstudiantesController::class, 'obtenerCursosXEstudianteAnioSemestre']);
    });
    Route::group(['prefix' => 'grados'], function () {
        Route::post('handleCrudOperation', [GradosController::class, 'handleCrudOperation']);
    });
    Route::group(['prefix' => 'docentes'], function () {
        Route::group(['prefix' => 'especialistas-dremo'], function () {
            Route::get('', [EspecialistasDremoController::class, 'obtenerEspecialistas']);
            Route::get('{docenteId}/areas', [EspecialistasDremoController::class, 'obtenerAreasPorEspecialista']);
            Route::post('{docenteId}/areas', [EspecialistasDremoController::class, 'asignarAreaEspecialista']);
            Route::delete('{docenteId}/areas', [EspecialistasDremoController::class, 'eliminarAreaEspecialista']);
        });
    });
    Route::group(['prefix' => 'cursos'], function () {
        Route::get('', [CursosController::class, 'listarCursosPorNivel']);
    });
});
