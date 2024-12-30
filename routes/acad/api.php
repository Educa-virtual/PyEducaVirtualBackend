<?php

use App\Http\Controllers\acad\EstudiantesController;
use App\Http\Controllers\acad\GradosController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'acad'], function () {

    Route::group(['prefix' => 'estudiantes'], function () {
        Route::post('obtenerCursosXEstudianteAnioSemestre', [EstudiantesController::class, 'obtenerCursosXEstudianteAnioSemestre']);
    });
    Route::group(['prefix' => 'grados'], function () {
        Route::post('handleCrudOperation', [GradosController::class, 'handleCrudOperation']);
    });
});
