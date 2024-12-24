<?php

use App\Http\Controllers\aula\AulaVirtualController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\acad\MatriculaController;
use App\Http\Controllers\aula\AnuncioController;
use App\Http\Controllers\aula\ForosController;
use App\Http\Controllers\aula\NotificacionController;
use App\Http\Controllers\aula\NotificacionEstudianteController;
use App\Http\Controllers\aula\ProgramacionActividadesController;
use App\Http\Controllers\aula\ResultadoController;
use App\Http\Controllers\aula\TareaCabeceraGruposController;
use App\Http\Controllers\aula\TareaEstudiantesController;
use App\Http\Controllers\aula\TareasController;
use App\Http\Controllers\aula\TipoActividadController;
use Illuminate\Notifications\Notification;

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
            Route::post('maestroDetalle', [AulaVirtualController::class, 'maestroDetalle']);
            Route::post('guardarForo', [AulaVirtualController::class, 'guardarForo']);
            Route::post('obtenerCategorias', [AulaVirtualController::class, 'obtenerCategorias']);
            Route::post('obtenerCalificacion', [AulaVirtualController::class, 'obtenerCalificacion']);
            Route::get('obtenerForo', [AulaVirtualController::class, 'obtenerForo']);
            Route::post('guardarRespuesta', [AulaVirtualController::class, 'guardarRespuesta']);
            Route::get('obtenerRespuestaForo', [AulaVirtualController::class, 'obtenerRespuestaForo']);
            Route::post('calificarForoDocente', [AulaVirtualController::class, 'calificarForoDocente']);
            Route::post('guardarComentarioRespuesta', [AulaVirtualController::class, 'guardarComentarioRespuesta']);
            Route::get('obtenerEstudiantesMatricula', [AulaVirtualController::class, 'obtenerEstudiantesMatricula']);
            Route::delete('eliminarRptEstudiante', [AulaVirtualController::class, 'eliminarRptEstudiante']);            

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
        Route::post('eliminarEstudianteTarea', [TareaEstudiantesController::class, 'eliminarEstudianteTarea']);
    });
    Route::group(['prefix' => 'tarea-cabecera-grupos'], function () {
        Route::post('list', [TareaCabeceraGruposController::class, 'list']);
        Route::post('store', [TareaCabeceraGruposController::class, 'store']);
        Route::post('eliminarTareaCabeceraGrupos', [TareaCabeceraGruposController::class, 'eliminarTareaCabeceraGrupos']);
        Route::post('guardarCalificacionTareaCabeceraGruposDocente', [TareaCabeceraGruposController::class, 'guardarCalificacionTareaCabeceraGruposDocente']);
        Route::post('transferenciaTareaCabeceraGrupos', [TareaCabeceraGruposController::class, 'transferenciaTareaCabeceraGrupos']);
        Route::post('entregarEstudianteTareaGrupal', [TareaCabeceraGruposController::class, 'entregarEstudianteTareaGrupal']);
    });

    Route::group(['prefix' => 'Resultado'], function () {
        Route::get('obtenerResultados', [ResultadoController::class, 'obtenerResultados']);
        Route::post('guardarCalfcEstudiante', [ResultadoController::class, 'guardarCalfcEstudiante']);
        Route::post('obtenerCalificacionesFinalesReporte', [ResultadoController::class, 'obtenerCalificacionesFinalesReporte']);
        Route::post('habilitarCalificacion', [ResultadoController::class, 'habilitarCalificacion']);
        Route::get('obtenerReporteFinalNotas', [ResultadoController::class, 'obtenerReporteFinalNotas']);
        Route::get('reporteDeLogros', [ResultadoController::class, 'reporteDeLogros']);
        Route::get('reporteDeLogroFinalXYear', [ResultadoController::class, 'reporteDeLogroFinalXYear']); 
        Route::get('generarReporteDeLogrosAlcanzadosXYear', [ResultadoController::class, 'generarReporteDeLogrosAlcanzadosXYear']);
        
    });
    Route::group(['prefix' => 'Anuncio'], function (){
        Route::post('guardarAnuncio', [AnuncioController::class, 'guardarAnuncio']);
        Route::get('obtenerAnunciosXDocente', [AnuncioController::class, 'obtenerAnunciosXDocente']);
    });

    Route::group(['prefix' => 'foros'], function () {
        Route::post('obtenerForoxiForoId', [ForosController::class, 'obtenerForoxiForoId']);
        Route::post('actualizarForo', [ForosController::class, 'actualizarForo']);
    });

    Route::group(['prefix' => 'notificacion_docente'], function () {
        Route::post('mostrar_notificacion', [NotificacionController::class, 'mostrar_notificacion']);
    });
    Route::group(['prefix' => 'notificacion_estudiante'], function () {
        Route::post('mostrar_notificacion', [NotificacionEstudianteController::class, 'mostrar_notificacion']);
    });
});
