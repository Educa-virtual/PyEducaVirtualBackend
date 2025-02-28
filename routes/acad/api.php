
<?php

use App\Http\Controllers\acad\EstudiantesController;
use App\Http\Controllers\acad\GradosController;
use App\Http\Controllers\VacantesController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'acad'], function () {
    Route::group(['prefix' => 'vacantes'], function () {
        Route::post('guardar', [VacantesController::class, 'guardarVacantes']);
        //vacantes convenciones de nombre para APIs
    });
    Route::group(['prefix' => 'estudiantes'], function () {
        Route::post('obtenerCursosXEstudianteAnioSemestre', [EstudiantesController::class, 'obtenerCursosXEstudianteAnioSemestre']);
    });
    Route::group(['prefix' => 'grados'], function () {
        Route::post('handleCrudOperation', [GradosController::class, 'handleCrudOperation']);
    });
});
