<?php

use App\Http\Controllers\acad\MatriculaController;
use App\Http\Controllers\aula\AulaVirtualController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'aula-virtual'], function () {
    Route::group(['prefix' => 'contenidos'], function () {
        Route::group(['prefix' => 'actividad'], function () {
            Route::post('guardarActividad', [AulaVirtualController::class, 'guardarActividad']);
            Route::delete('eliminarActividad', [AulaVirtualController::class, 'eliminarActividad']);
            Route::get('obtenerActividad', [AulaVirtualController::class, 'obtenerActividad']);
            //agregando para asignar estudiantes
            Route::post('asignar-estudiantes', [AulaVirtualController::class, 'asignarEstudiantes']);
        });
        Route::group(['prefix' => 'foro'], function () {
            Route::post('guardarForo', [AulaVirtualController::class, 'guardarForo']);
        });

        Route::get('contenidoSemanasProgramacionActividades', [AulaVirtualController::class, 'contenidoSemanasProgramacionActividades']);
    });

    Route::group(['prefix' => 'matricula'], function () {
        Route::post('list', [MatriculaController::class, 'list']);
    });
});
