<?php

use App\Http\Controllers\acad\EstudiantesController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'acad'], function () {

    Route::group(['prefix' => 'estudiantes'], function () {
        Route::post('obtenerCursosXEstudianteAnioSemestre', [EstudiantesController::class, 'obtenerCursosXEstudianteAnioSemestre']);
    });
});
