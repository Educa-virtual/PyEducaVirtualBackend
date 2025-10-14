<?php

use App\Http\Controllers\asi\AsistenciaControlController;
use App\Http\Controllers\asi\AsistenciaController;
use App\Http\Controllers\asi\AsistenciaGeneralController;
use App\Http\Middleware\RefreshToken;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'asi', 'middleware' => ['auth:api', RefreshToken::class]], function () {
    Route::group(['prefix' => 'grupos'], function () {
        Route::post('verificar-grupo-asistencia', [AsistenciaController::class, 'verificarGrupoAsistencia']);
        Route::post('guardar-grupo-asistencia', [AsistenciaController::class, 'guardarGrupo']);
        Route::post('actualizar-grupo-asistencia', [AsistenciaController::class, 'actualizarGrupo']);
        Route::post('verificar-horario', [AsistenciaController::class, 'verificarHorarioAsistencia']);
        Route::post('buscar-horario-ie', [AsistenciaController::class, 'buscarHorarioInstitucion']);
        Route::post('buscar-personal-ie', [AsistenciaController::class, 'buscarPersonalInstitucion']);
        Route::post('editar-grupos-ie', [AsistenciaController::class, 'editarGrupoInstitucion']);
        Route::post('guardar-persona-grupo', [AsistenciaController::class, 'guardarPersonalGrupo']);
        Route::post('buscar-lista-estudiantes', [AsistenciaController::class, 'buscarAlumnos']);
        Route::post('buscar-reporte', [AsistenciaController::class, 'buscarAsisnteciaGeneral']);
        Route::post('guardar-asistencia', [AsistenciaController::class, 'guardarAsistenciaEstudiante']);
        Route::post('guardar-asistencia-aula', [AsistenciaController::class, 'guardarAsistenciaGeneral']);
    });
    Route::group(['prefix' => 'asistencia'], function () {
        Route::group(['prefix' => 'general/{anio}/{mes}'], function () {
            Route::get('estudiante', [AsistenciaGeneralController::class, 'obtenerAsistenciaEstudiantePorFecha']);
            Route::get('apoderado/estudiante/{iMatrId}', [AsistenciaGeneralController::class, 'obtenerAsistenciaEstudianteApoderadoPorFecha']);
        });
        Route::group(['prefix' => 'control/{fecha}'], function () {
            Route::get('estudiante', [AsistenciaControlController::class, 'obtenerAsistenciaEstudiantePorFecha']);
        });
        Route::post('descargar-justificacion', [AsistenciaController::class, 'descargarJustificacion']);
    });
});
