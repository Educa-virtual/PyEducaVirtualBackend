<?php

use App\Http\Controllers\aula\AulaVirtualController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\acad\MatriculaController;
use App\Http\Controllers\aula\AcademicoController;
use App\Http\Controllers\aula\AnunciosController;
use App\Http\Controllers\aula\CuestionariosController;
use App\Http\Controllers\aula\ForosController;
use App\Http\Controllers\aula\NotificacionController;
use App\Http\Controllers\aula\NotificacionEstudianteController;
use App\Http\Controllers\aula\ProgramacionActividadesController;
use App\Http\Controllers\aula\ResultadoController;
use App\Http\Controllers\aula\TareaCabeceraGruposController;
use App\Http\Controllers\aula\TareaEstudiantesController;
use App\Http\Controllers\aula\TareasController;
use App\Http\Controllers\aula\TipoActividadController;
use App\Http\Controllers\aula\EstadisticasController;
use App\Http\Controllers\aula\PreguntaAlternativasRespuestasController;
use App\Http\Controllers\aula\PreguntasController;
use App\Http\Controllers\aula\ReunionVirtualesController;
use App\Http\Controllers\aula\TipoExperienciaAprendizajeController;
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
            Route::get('obtenerReptdocente', [AulaVirtualController::class, 'obtenerReptdocente']);
            Route::get('listaEstudiantes', [ForosController::class, 'obtenerListaEstudiantes']);
        });
        Route::get('contenidoSemanasProgramacionActividades', [AulaVirtualController::class, 'contenidoSemanasProgramacionActividades']);
    });

    Route::group(['prefix' => 'matricula'], function () {
        Route::post('list', [MatriculaController::class, 'list']);
        Route::post('registrar', [MatriculaController::class, 'registrar']);
    });
    Route::group(['prefix' => 'programacion-actividades'], function () {
        Route::post('list', [ProgramacionActividadesController::class, 'list']);
        Route::post('store', [ProgramacionActividadesController::class, 'store']);
    });
    Route::group(['prefix' => 'tareas'], function () {
        Route::post('', [TareasController::class, 'guardarTareas']);
        Route::put('{iTareaId}', [TareasController::class, 'actualizarTareasxiTareaId']);

        ////
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
    Route::group(['prefix' => 'anuncios'], function () {
        Route::post('guardarAnuncios', [AnunciosController::class, 'guardarAnuncios']);
        Route::post('listarAnuncios', [AnunciosController::class, 'listarAnuncios']);
        Route::post('eliminarAnuncios', [AnunciosController::class, 'eliminarAnuncios']);
        Route::post('fijarAnuncios', [AnunciosController::class, 'fijarAnuncios']);
    });

    Route::group(['prefix' => 'foros'], function () {
        Route::post('obtenerForoxiForoId', [ForosController::class, 'obtenerForoxiForoId']);
        Route::post('actualizarForo', [ForosController::class, 'actualizarForo']);
        Route::post('eliminarxiForoId', [ForosController::class, 'eliminarxiForoId']);
        Route::post('/', [ForosController::class, 'guardarForos']); // Para crear
        Route::post('obtenerReporteEstudiantesRetroalimentacion', [ForosController::class, 'obtenerReporteEstudiantesRetroalimentacion']); // Para crear

    });

    Route::group(['prefix' => 'notificacion_docente'], function () {
        Route::post('mostrar_notificacion', [NotificacionController::class, 'mostrar_notificacion']);
    });
    Route::group(['prefix' => 'notificacion_estudiante'], function () {
        Route::post('mostrar_notificacion', [NotificacionEstudianteController::class, 'mostrar_notificacion']);
    });
    Route::group(['prefix' => 'academico'], function () {
        Route::post('obtener_datos', [AcademicoController::class, 'obtenerDatos']);
        Route::post('reporte_academico', [AcademicoController::class, 'reporte']);
        Route::post('reporte_grado', [AcademicoController::class, 'reporteGrado']);
        Route::post('obtener_academico_grado', [AcademicoController::class, 'obtenerAcademicoGrado']);
        Route::post('/reporte_ranking', [EstadisticasController::class, 'generarReporteNotas']);
        Route::post('/guardar_record', [EstadisticasController::class, 'guardarRecord']);
        Route::post('/obtener-reportes', [EstadisticasController::class, 'obtenerReportes']);
        Route::post('/eliminar-record', [EstadisticasController::class, 'eliminarRecord']);

        Route::post('/estadistica/grados-por-sede', [EstadisticasController::class, 'obtenerGradosPorSede']);
        Route::post('/estadistica/generar-reporte', [EstadisticasController::class, 'generarReporteNotas']);
    });
    Route::group(['prefix' => 'reunion-virtuales'], function () {
        Route::post('/', [ReunionVirtualesController::class, 'guardarReunionVirtuales']);
        Route::put('/{iRVirtualId}', [ReunionVirtualesController::class, 'actualizarReunionVirtuales']);
        Route::delete('/{iRVirtualId}', [ReunionVirtualesController::class, 'eliminarReunionVirtuales']);
        Route::get('/{iRVirtualId}', [ReunionVirtualesController::class, 'obtenerReunionVirtualesxiRVirtualId']);
    });
    Route::prefix('cuestionarios')->group(function () {
        Route::post('/', [CuestionariosController::class, 'guardarCuestionario']); // Para crear
        Route::put('/{iCuestionarioId}', [CuestionariosController::class, 'actualizarCuestionario']); // Para actualizar
        Route::delete('/{iCuestionarioId}', [CuestionariosController::class, 'eliminarCuestionario']); // Para eliminar
        Route::get('/{iCuestionarioId}', [CuestionariosController::class, 'obtenerCuestionarioxiCuestionarioId']); // Para obtener un cuestionario específico
    });
    Route::prefix('preguntas')->group(function () {
        Route::post('/', [PreguntasController::class, 'guardarPreguntas']); // Para crear
        Route::put('/{iPregId}', [PreguntasController::class, 'actualizarPreguntasxiPregId']); // Para actualizar
        Route::delete('/{iPregId}', [PreguntasController::class, 'eliminarPreguntaxiPregId']); // Para eliminar
        Route::get('/cuestionario/{iCuestionarioId}', [PreguntasController::class, 'listarPreguntasxiCuestionarioId']); // Para obtener las preguntas de un cuestionario específico
    });
    Route::prefix('pregunta-alternativas-respuestas')->group(function () {
        Route::get('/cuestionario/{iCuestionarioId}/estudiante/{iEstudianteId}', [PreguntaAlternativasRespuestasController::class, 'listarPreguntasxiCuestionarioIdxiEstudianteId']); // Para obtener las preguntas del cuestionario del estudiante
        Route::put('/cuestionario/{iCuestionarioId}/estudiante/{iEstudianteId}', [PreguntaAlternativasRespuestasController::class, 'guardarPreguntasxiCuestionarioIdxiEstudianteId']); // Para guardar las preguntas del cuestionario del estudiante
        Route::put('/cuestionario/{iCuestionarioId}/estudiante/{iEstudianteId}/finalizado', [PreguntaAlternativasRespuestasController::class, 'finalizarPreguntaAlternativasRespuestas']); // Para finalizar las preguntas del cuestionario del estudiante
        Route::get('/cuestionario/{iCuestionarioId}/resultados', [PreguntaAlternativasRespuestasController::class, 'obtenerResultadosxiCuestionarioId']);
    });
    Route::prefix('tipo-experiencia-aprendizaje')->group(function () {
        Route::get('/', [TipoExperienciaAprendizajeController::class, 'listarTipoExperienciaAprendizaje']); // Para obtener los tipos de experiencia de aprendizaje
    });
});
