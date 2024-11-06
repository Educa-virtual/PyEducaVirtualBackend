<?php

use App\Http\Controllers\aula\AulaVirtualController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\acad\MatriculaController;
use App\Http\Controllers\aula\ProgramacionActividadesController;
use App\Http\Controllers\aula\TareaCabeceraGruposController;
use App\Http\Controllers\aula\TareaEstudiantesController;
use App\Http\Controllers\aula\TareasController;
use App\Http\Controllers\aula\TipoActividadController;

Route::group(['prefix' => 'aula-virtual'], function () {
    Route::group(['prefix' => 'contenidos'], function () {
        Route::resource('tipo-actividad', TipoActividadController::class);
        Route::group(['prefix' => 'actividad'], function () {
            Route::post('guardarActividad', [AulaVirtualController::class, 'guardarActividad']);
            Route::delete('eliminarActividad', [AulaVirtualController::class, 'eliminarActividad']);
            Route::get('obtenerActividad', [AulaVirtualController::class, 'obtenerActividad']);
            //agregando para asignar estudiantes
            Route::post('asignar-estudiantes', [AulaVirtualController::class, 'asignarEstudiantes']);
        });
        Route::group(['prefix' => 'foro'], function () {
            Route::post('guardarForo', [AulaVirtualController::class, 'guardarForo']);
            Route::post('obtenerCategorias', [AulaVirtualController::class, 'obtenerCategorias']);
            Route::post('obtenerCalificacion', [AulaVirtualController::class, 'obtenerCalificacion']);
            Route::get('obtenerForo', [AulaVirtualController::class, 'obtenerForo']);
            Route::post('guardarRespuesta', [AulaVirtualController::class, 'guardarRespuesta']);
            Route::get('obtenerRespuestaForo', [AulaVirtualController::class, 'obtenerRespuestaForo']);
            Route::post('calificarForoDocente', [AulaVirtualController::class, 'calificarForoDocente']);
        });

        Route::get('contenidoSemanasProgramacionActividades', [AulaVirtualController::class, 'contenidoSemanasProgramacionActividades']);
    });

    Route::group(['prefix' => 'matricula'], function () {
        Route::post('list', [MatriculaController::class, 'list']);
    });
    Route::group(['prefix' => 'programacion-actividades'], function () {
        Route::post('list', [ProgramacionActividadesController::class, 'list']);
        Route::post('store', [ProgramacionActividadesController::class, 'store']);
    });
    Route::group(['prefix' => 'tareas'], function () {
        Route::post('list', [TareasController::class, 'list']);
        Route::post('store', [TareasController::class, 'store']);
        Route::post('getTareasxiCursoId', [TareasController::class, 'getTareasxiCursoId']);
        Route::post('delete', [TareasController::class, 'delete']);
        Route::post('crear-actualizar-grupo', [TareasController::class, 'crearActualizarGrupo']);
        Route::post('updatexiTareaId', [TareasController::class, 'updatexiTareaId']);
        Route::post('obtenerTareaxiTareaidxiEstudianteId', [TareasController::class, 'obtenerTareaxiTareaidxiEstudianteId']);
    });
    Route::group(['prefix' => 'tarea-estudiantes'], function () {
        Route::post('list', [TareaEstudiantesController::class, 'list']);
        Route::post('store', [TareaEstudiantesController::class, 'store']);
        Route::post('guardar-calificacion-docente', [TareaEstudiantesController::class, 'guardarCalificacionDocente']);
        Route::post('entregarEstudianteTarea', [TareaEstudiantesController::class, 'entregarEstudianteTarea']);
    });
    Route::group(['prefix' => 'tarea-cabecera-grupos'], function () {
        Route::post('list', [TareaCabeceraGruposController::class, 'list']);
        Route::post('store', [TareaCabeceraGruposController::class, 'store']);
        Route::post('eliminarTareaCabeceraGrupos', [TareaCabeceraGruposController::class, 'eliminarTareaCabeceraGrupos']);
        Route::post('guardarCalificacionTareaCabeceraGruposDocente', [TareaCabeceraGruposController::class, 'guardarCalificacionTareaCabeceraGruposDocente']);
        Route::post('transferenciaTareaCabeceraGrupos', [TareaCabeceraGruposController::class, 'transferenciaTareaCabeceraGrupos']);
    });
});
