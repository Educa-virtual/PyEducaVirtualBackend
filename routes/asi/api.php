<?php

use App\Http\Controllers\asi\AsistenciaController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'asi'], function () {
    Route::group(['prefix' => 'grupos'], function () {
        Route::post('verificar-grupo-asistencia', [AsistenciaController::class, 'verificarGrupoAsistencia']);
        Route::post('guardar-grupo-asistencia', [AsistenciaController::class, 'guardarGrupo']);
        Route::post('actualizar-grupo-asistencia', [AsistenciaController::class, 'actualizarGrupo']);
        Route::post('verificar-horario', [AsistenciaController::class, 'verificarHorarioAsistencia']);
        Route::post('buscar-horario-ie', [AsistenciaController::class, 'buscarHorarioInstitucion']);
        Route::post('buscar-personal-ie', [AsistenciaController::class, 'buscarPersonalInstitucion']);
        Route::post('editar-grupos-ie', [AsistenciaController::class, 'editarGrupoInstitucion']);
        Route::post('guardar-persona-grupo', [AsistenciaController::class, 'guardarPersonalGrupo']);
    });
});
