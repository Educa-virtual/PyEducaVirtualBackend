<?php

use App\Http\Controllers\acad\ApoderadoController;
use App\Http\Controllers\acad\EstudiantesController;
use App\Http\Controllers\acad\GradosController;
use App\Http\Controllers\acad\MatriculaController;
use App\Http\Controllers\ere\InstitucionesEducativasController;
use App\Http\Controllers\ere\CapacidadesController;
use App\Http\Controllers\ere\CompetenciasController;
use App\Http\Controllers\ere\DesempenosController;

use App\Http\Controllers\api\acad\ActividadesAprendizajeController;
use App\Http\Controllers\api\acad\BibliografiaController;
use App\http\Controllers\api\acad\CalendarioAcademicosController;
use App\http\Controllers\api\acad\GestionInstitucionalController;
use App\http\Controllers\api\acad\HorarioController;
use App\http\Controllers\api\acad\PeriodoAcademicosController;
use App\Http\Controllers\CredencialController;

use App\Http\Controllers\api\seg\ListarCursosController;
use App\Http\Controllers\api\acad\AutenticarUsurioController;
use App\Http\Controllers\api\acad\InstitucionesEducativasController as AcadInstitucionesEducativasController;
use App\Http\Controllers\api\grl\PersonaController;
use App\Http\Controllers\api\grl\TipoIdentificacionController;
use App\Http\Controllers\api\acad\SelectPerfilesController;
use App\Http\Controllers\api\seg\CredencialescCredUsuariocClaveController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ere\cursoController;
use App\Http\Controllers\ere\EvaluacionesController;
use App\Http\Controllers\ere\NivelEvaluacionController;
use App\Http\Controllers\ere\NivelTipoController;
use App\Http\Controllers\ere\TipoEvaluacionController;
use App\Http\Controllers\ere\UgelesController;
use App\Http\Controllers\seg\AuditoriaAccesosController;
use App\Http\Controllers\seg\AuditoriaAccesosFallidosController;
use App\Http\Controllers\seg\AuditoriaController;
use App\Http\Controllers\seg\AuditoriaMiddlewareController;
use App\Http\Controllers\seg\CredencialesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\aula\EstadisticasController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['prefix' => 'ere'], function () {

    Route::group(['prefix' => 'ie'], function () {
        Route::get('obtenerIE', [InstitucionesEducativasController::class, 'obtenerInstitucionesEducativas']);
    });
    Route::group(['prefix' => 'nivelTipo'], function () {
        Route::get('obtenerNivelTipo', [NivelTipoController::class, 'obtenerNivelTipo']);
    });

    Route::group(['prefix' => 'tipoEvaluacion'], function () {
        Route::get('obtenerTipoEvaluacion', [TipoEvaluacionController::class, 'obtenerTipoEvaluacion']);
    });

    Route::group(['prefix' => 'nivelEvaluacion'], function () {
        Route::get('obtenerNivelEvaluacion', [NivelEvaluacionController::class, 'obtenerNivelEvaluacion']);
    });

    Route::group(['prefix' => 'competencias'], function () {
        Route::get('obtenerCompetencias', [CompetenciasController::class, 'obtenerCompetencias']);
    });

    Route::group(['prefix' => 'capacidades'], function () {
        Route::get('obtenerCapacidades', [CapacidadesController::class, 'obtenerCapacidades']);
    });


    Route::group(['prefix' => 'desempenos'], function () {
        Route::get('obtenerDesempenos', [DesempenosController::class, 'obtenerDesempenos']);
    });

    Route::group(['prefix' => 'curso'], function () {
        Route::get('obtenerCursos', [cursoController::class, 'obtenerCursos']);
    });
    Route::group(['prefix' => 'Evaluaciones'], function () {
        Route::get('ereObtenerEvaluacion', [EvaluacionesController::class, 'obtenerEvaluaciones']); // Cambié el nombre de la ruta para que sea más limpio

        Route::get('obtenerUltimaEvaluacion', [EvaluacionesController::class, 'obtenerUltimaEvaluacion']);
        Route::post('guardar', [EvaluacionesController::class, 'guardarEvaluacion']);
        Route::post('actualizar', [EvaluacionesController::class, 'actualizarEvaluacion']);
        //Agregando participacion y eliminando participacion, IE
        Route::post('guardarParticipacion', [EvaluacionesController::class, 'guardarParticipacion']);
        Route::delete('eliminarParticipacion', [EvaluacionesController::class, 'eliminarParticipacion']);
        //Agregando participacion nuevo
        Route::post('guardarParticipacionNuevo', [EvaluacionesController::class, 'guardarParticipacionNuevo']);
        // Ruta para actualizar la evaluación
        Route::put('actualizar/{iEvaluacionId}', [EvaluacionesController::class, 'actualizarEvaluacion']);
        // Ruta para obtener las participaciones
        //Route::get('obtenerParticipaciones', [EvaluacionesController::class, 'obtenerParticipaciones']);
        Route::get('obtenerParticipaciones/{iEvaluacionId}', [EvaluacionesController::class, 'obtenerParticipaciones']);
        //Nuevo Ver con Datos completos
        Route::get('verParticipacionNuevo', [EvaluacionesController::class, 'verParticipacionNuevo']);
        //Obtener Cursos
        Route::post('obtenerCursos', [EvaluacionesController::class, 'obtenerCursos']);
        //Insertar Cursos
        Route::post('insertarCursos', [EvaluacionesController::class, 'insertarCursos']);
        //Eliminar Cursos
        Route::delete('eliminarCursos', [EvaluacionesController::class, 'eliminarCursos']);
        //Ver Cursos
        Route::get('evaluaciones/{iEvaluacionId}/cursos', [EvaluacionesController::class, 'obtenerCursosEvaluacion']);
        //Actualizar Cursos COMENTADO
        Route::post('evaluaciones/{iEvaluacionId}/actualizarCursos', [EvaluacionesController::class, 'actualizarCursosEvaluacion']);
        //Obtener Evaluacion Copiar
        Route::get('/obtenerEvaluacionCopia', [EvaluacionesController::class, 'obtenerEvaluacionCopia']);
        //Obtener evaluacion Copiar 2
        Route::get('/obtenerEvaluacionCopia2', [EvaluacionesController::class, 'obtenerEvaluacionCopia2']);
        // ACTUALIZAAR En routes/api.php o routes/web.php
        Route::put('actualizarCursos', [EvaluacionesController::class, 'actualizarCursos']);
        //Agregando CopiarEvaluacion
        Route::post('copiarEvaluacion', [EvaluacionesController::class, 'copiarEvaluacion']);
        //ObtenerMatrizCompetencia
        Route::get('obtenerMatrizCompetencias', [EvaluacionesController::class, 'obtenerMatrizCompetencias']);
        //ObtenerMatrizCapacidad
        Route::get('obtenerMatrizCapacidades', [EvaluacionesController::class, 'obtenerMatrizCapacidades']);
        //InsertarMatrizDesempeno
        Route::post('insertarMatrizDesempeno', [EvaluacionesController::class, 'insertarMatrizDesempeno']);
        //ObtenerEspecialistas
        Route::get('obtenerEspDrem', [EvaluacionesController::class, 'obtenerEspDrem']);
        //Obtener por el iGradoId los Cursos del Especialista
        Route::get('obtenerEspDremCurso', [EvaluacionesController::class, 'obtenerEspDremCurso']);
        //Matriz Descargar
        Route::get('generarPdfMatrizbyEvaluacionId', [EvaluacionesController::class, 'generarPdfMatrizbyEvaluacionId']);
        //Insertar pregunta seleccionada
        Route::post('insertarPreguntaSeleccionada', [EvaluacionesController::class, 'insertarPreguntaSeleccionada']);
        //Obtener pregunta seleccionada
        Route::get('obtenerPreguntaSeleccionada', [EvaluacionesController::class, 'obtenerPreguntaSeleccionada']);
        //Obtener preguntas por EvaluacionId y preguntaId
        Route::get('obtenerPreguntaInformacion', [EvaluacionesController::class, 'obtenerPreguntaInformacion']);
        //Obtener conteo por curso
        Route::post('obtenerConteoPorCurso', [EvaluacionesController::class, 'obtenerConteoPorCurso']);
        //Guardar fecha inicio fin de cursos
        Route::post('guardarInicioFinalExmAreas', [EvaluacionesController::class, 'guardarInicioFinalExmAreas']);
        //Eliminar una pregunta de una evaluación.
        Route::delete('eliminarPregunta', [EvaluacionesController::class, 'eliminarPregunta']);
         //guardar Fecha de Inicio y Cantidad de preguntas en examen cursos
         Route::post('guardarFechaCantidadExamenCursos', [EvaluacionesController::class, 'guardarFechaCantidadExamenCursos']);
        // Obtener evaluaciones, cursos, ies, grados, secciones según año académico
        Route::post('obtenerEvaluacionesCursosIes', [EvaluacionesController::class, 'obtenerEvaluacionesCursosIes']);
        // Obtener resumen de resultados de evaluacion
        Route::post('obtenerInformeResumen', [EvaluacionesController::class, 'obtenerInformeResumen']);
    });
    Route::group(['prefix' => 'Ugeles'], function () {
        Route::get('obtenerUgeles', [UgelesController::class, 'obtenerUgeles']);
    });
});
Route::group(['prefix' => 'acad'], function () {

    Route::group(['prefix' => 'AutenticarU'], function () {
        Route::get('obtenerAutenticacion', [AutenticarUsurioController::class, 'obtenerAutenticacion']);
    });

    Route::group(['prefix' => 'Perfiles'], function () {
        Route::get('obtenerPerfiles', [SelectPerfilesController::class, 'obtenerPerfiles']);
    });

    Route::group(['prefix' => 'institucionEducativa'], function () {
        Route::get('selReglamentoInterno', [AcadInstitucionesEducativasController::class, 'selReglamentoInterno']);
        Route::put('updReglamentoInterno', [AcadInstitucionesEducativasController::class, 'updReglamentoInterno']);
    });
    Route::group(['prefix' => 'gestionInstitucional'], function () {
        Route::post('listarPersonalIes', [GestionInstitucionalController::class, 'listarPersonalIes']);
        //procendimiento generales
        Route::post('insertMaestroDetalle', [GestionInstitucionalController::class, 'insertMaestroDetalle']);
        Route::post('insertMaestro', [GestionInstitucionalController::class, 'insertMaestro']);
        Route::post('updateMaestro', [GestionInstitucionalController::class, 'updateMaestro']);
        Route::post('deleteMaestro', [GestionInstitucionalController::class, 'deleteMaestro']);
        Route::post('reporteHorasNivelGrado', [GestionInstitucionalController::class, 'reporteHorasNivelGrado']);
        Route::post('reporteSeccionesNivelGrado', [GestionInstitucionalController::class, 'reporteSeccionesNivelGrado']);
        Route::post('reportePDFResumenAmbientes', [GestionInstitucionalController::class, 'reportePDFResumenAmbientes']);
        Route::post('obtenerInformacionEstudianteDNI', [GestionInstitucionalController::class, 'obtenerInformacionEstudianteDNI']);
        Route::post('obtenerCredencialesSede', [GestionInstitucionalController::class, 'obtenerCredencialesSede']);
        Route::post('importarDocente_IE', [GestionInstitucionalController::class, 'importarDocente_IE']);
        Route::post('importarAmbiente_IE', [GestionInstitucionalController::class, 'importarAmbiente_IE']);
    });

    Route::group(['prefix' => 'horario'], function () {
        Route::post('listarHorarioIes', [HorarioController::class, 'listarHorarioIes']);
        //procendimiento generales

    });

    Route::post('calendarioAcademicos/searchAmbiente', [CalendarioAcademicosController::class, 'selAmbienteAcademico']); //Cambio Alvaro Ere

    Route::group(['prefix' => 'calendarioAcademico'], function () {
        Route::post('addCalAcademico', [CalendarioAcademicosController::class, 'addCalAcademico']); // procedimiento especifico EXEC acad.SP_INS_stepCalendarioAcademicoDesdeJsonOpcion ?,?
        Route::post('updateCalAcademico', [CalendarioAcademicosController::class, 'updateCalAcademico']); //procedimiento especifico EXEC acad.SP_UPD_stepCalendarioAcademicoDesdeJsonOpcion ?,?
        Route::post('deleteCalAcademico', [CalendarioAcademicosController::class, 'deleteCalAcademico']); // procedimiento especifico EXEC acad.SP_DEL_stepCalendarioAcademicoDesdeJsonOpcion ?,?
        Route::post('searchAcademico', [CalendarioAcademicosController::class, 'searchAcademico']); // procedimiento especifico EXEC acad.SP_SEL_stepCalendarioAcademicoDesdeJsonOpcion ?,?

        Route::post('searchCalAcademico', [CalendarioAcademicosController::class, 'searchCalAcademico']); // procedimiento general
        Route::post('updateCalendario', [CalendarioAcademicosController::class, 'updateCalendario']); // procedimiento general
        Route::post('deleteCalendario', [CalendarioAcademicosController::class, 'deleteCalendario']); // procediiento general

        Route::post('addYear', [CalendarioAcademicosController::class, 'addYear']); // procedimiento especifico SP_INS_TablaYearXopcion
        Route::post('updateYear', [CalendarioAcademicosController::class, 'updateYear']); // procedimiento especifico SP_UPD_TablaYearXopcion
        Route::post('deleteYear', [CalendarioAcademicosController::class, 'deleteYear']); // procedimiento especifico SP_DEL_TablaYearXopcion

        Route::post('addAmbiente', [CalendarioAcademicosController::class, 'addAmbienteAcademico']); // procedimiento especifico  acad.SP_INS_stepAmbienteAcademicoDesdeJsonOpcion
        Route::post('searchAmbiente', [CalendarioAcademicosController::class, 'selAmbienteAcademico']); // procedimiento especifico acad.SP_SEL_stepAmbienteAcademicoDesdeJsonOpcion ?,?
        Route::post('searchGradoCiclo', [CalendarioAcademicosController::class, 'searchGradoCiclo']); // procedimiento especifico acad.SP_SEL_generarGradosSeccionesCiclosXiNivelTipoId ?


        /*
         * * Peticiones de información de varios calendarios
        */
        //* GET: Calendarios académicos por sede
        Route::get('selCalAcademicoSede', [CalendarioAcademicosController::class, 'selCalAcademicoSede']);

        Route::get('selDiasLaborales', [CalendarioAcademicosController::class, 'selDiasLaborales']);

        /*
         * * Peticiones de información para la configuración de un calendario
        */
        //* GET: Fases promocionales y fechas para configurar un calendario
        Route::get('selCalFasesProm', [CalendarioAcademicosController::class, 'selCalFasesProm']);

        /*
         * * Peticiones de información para la configuración de un calendario
        */
        //* GET: Fases promocionales y fechas para configurar un calendario
        Route::get('selFasesFechas', [CalendarioAcademicosController::class, 'selFasesFechas']);

        //* GET: Fases promocionales y fechas para configurar un calendario
        Route::get('selFasesFechas', [CalendarioAcademicosController::class, 'selFasesFechas']);

        //* GET: Dias laborales para configurar un calendario
        Route::get('selTurnosModalidades', [CalendarioAcademicosController::class, 'selTurnosModalidades']);

        //* GET: Periodos de evaluaciones formativos
        Route::get('selPeriodosFormativos', [CalendarioAcademicosController::class, 'selPeriodosFormativos']);

        //* GET: Dias laborales de un calendario
        Route::get('selCalDiasLaborales', [CalendarioAcademicosController::class, 'selCalDiasLaborales']);

        // //* GET: Formas y modalidades de atención para configurar un calendario
        // Route::get('selFormasAtencion', [CalendarioAcademicosController::class, 'selFormasAtencion']);

        /*
         * * Peticiones de información de un calendario
        */
        // * GET: Toda la información de un calendario
        Route::get('selCalAcademico', [CalendarioAcademicosController::class, 'selCalAcademico']);

        // //* GET: Fases promocionales y fechas de un calendario académico
        // Route::get('selCalFasesFechas', [CalendarioAcademicosController::class, 'selCalFasesFechas']);

        //* GET: Dias Laborales de la semana
        Route::get('selDias', [CalendarioAcademicosController::class, 'selDias']);

        // //* GET: Formas de atención y sus modalidades de un calendario
        // Route::get('selCalFormasAtencion', [CalendarioAcademicosController::class, 'selCalFormasAtencion']);

        // //* GET: Periodos académicos de un calendario
        // Route::get('selCalPeriodosAcademicos', [CalendarioAcademicosController::class, 'selCalPeriodosAcademicos']);

        // //* GET: Información de un calendario configurado
        // Route::get('selCalAcademicoResumen', [CalendarioAcademicosController::class, 'selCalAcademicoResumen']);

        /*
         * * Peticiones con información para guardar información de un
         * * calendario
         */
        // * POST: Fases promocionales de un calendario académico
        Route::post('insCalFasesProm', [CalendarioAcademicosController::class, 'insCalFasesProm']);

        // * POST: Calendario académico
        Route::post('insCalAcademico', [CalendarioAcademicosController::class, 'insCalAcademico']);

        // * POST: Dias Laborales de un calendario
        Route::post('insCalDiasLaborales', [CalendarioAcademicosController::class, 'insCalDiasLaborales']);

        //* POST: Formas de atención y sus modalidades de un calendario
        Route::post('insCalFormasAtencion', [CalendarioAcademicosController::class, 'insCalFormasAtencion']);

        //* POST: Periodos académicos de un calendario
        Route::post('insCalPeriodosFormativos', [CalendarioAcademicosController::class, 'insCalPeriodosFormativos']);

        // //* POST: Información de un calendario configurado
        // Route::post('insCalAcademicoResumen', [CalendarioAcademicosController::class, 'insCalAcademicoResumen']);

        /*
         * * Peticiones con información para editar información de un
         * * calendario
        */
        // * PUT: Calendario Académico
        Route::put('updCalAcademico', [CalendarioAcademicosController::class, 'updCalAcademico']);

        //* PUT: Formas de atención y sus modalidades de un calendario
        Route::put('updCalFormasAtencion', [CalendarioAcademicosController::class, 'updCalFormasAtencion']);

        //* PUT: Periodos académicos de un calendario
        Route::put('updCalFasesProm', [CalendarioAcademicosController::class, 'updCalFasesProm']);

        // //* PUT: Periodos académicos de un calendario
        // Route::put('updCalPeriodosAcademicos', [CalendarioAcademicosController::class, 'updCalPeriodosAcademicos']);


        /*
         * * Peticiones con información para eliminar información de un
         * * calendario
        */
        // * DELETE: Calendario Academico por identificador
        Route::delete('deleteCalFasesProm', [CalendarioAcademicosController::class, 'deleteCalFasesProm']);

        // * DELETE: Dias laborales del calendario
        Route::delete('deleteCalDiasLaborales', [CalendarioAcademicosController::class, 'deleteCalDiasLaborales']);

        // * DELETE: Calendario Academico por identificador
        // Route::delete('deleteCalAcademico', CalendarioAcademicosController::class, 'deleteCalAcademico');

        //* DELETE: Formas de atención y sus modalidades de un calendario
        Route::delete('deleteCalFormasAtencion', [CalendarioAcademicosController::class, 'deleteCalFormasAtencion']);

        //* DELETE: Periodos académicos de un calendario
        Route::delete('deleteCalPeriodosFormativos', [CalendarioAcademicosController::class, 'deleteCalPeriodosFormativos']);
    });

    Route::group(['prefix' => 'estudiante'], function () {

        Route::post('guardarEstudiante', [EstudiantesController::class, 'save']);
        Route::post('actualizarEstudiante', [EstudiantesController::class, 'update']);
        Route::post('searchEstudiantes', [EstudiantesController::class, 'index']);
        Route::post('searchEstudiante', [EstudiantesController::class, 'show']);

        Route::post('guardarApoderado', [ApoderadoController::class, 'save']);
        Route::post('actualizarApoderado', [ApoderadoController::class, 'update']);
        Route::post('searchApoderado', [ApoderadoController::class, 'show']);

        Route::post('importarEstudiantesPadresExcel', [EstudiantesController::class, 'importarEstudiantesPadresExcel']);
        Route::post('importarEstudiantesMatriculasExcel', [EstudiantesController::class, 'importarEstudiantesMatriculasExcel']);
    });

    Route::group(['prefix' => 'matricula'], function () {

        Route::post('searchGrados', [MatriculaController::class, 'searchGrados']);
        Route::post('searchSecciones', [MatriculaController::class, 'searchSecciones']);
        Route::post('searchTurnos', [MatriculaController::class, 'searchTurnos']);

        Route::post('searchGradoSeccionTurnoConf', [MatriculaController::class, 'searchGradoSeccionTurnoConf']);
        Route::post('searchNivelGrado', [MatriculaController::class, 'searchNivelGrado']);

        Route::post('searchMatriculas', [MatriculaController::class, 'index']);
        Route::post('searchMatricula', [MatriculaController::class, 'show']);
        Route::post('guardarMatricula', [MatriculaController::class, 'save']);
        Route::post('borrarMatricula', [MatriculaController::class, 'delete']);
    });
});

Route::group(['prefix' => 'seg'], function () {
    Route::group(['prefix' => 'auditoria'], function () {
        Route::post('selAuditoriaAccesos', [AuditoriaAccesosController::class, 'selAuditoriaAccesos']);
        Route::post('selAuditoriaAccesosFallidos', [AuditoriaAccesosFallidosController::class, 'selAuditoriaAccesosFallidos']);
        Route::post('selAuditoria', [AuditoriaController::class, 'selAuditoria']);
        Route::post('selAuditoriaMiddleware', [AuditoriaMiddlewareController::class, 'selAuditoriaMiddleware']);
    });
});


Route::post('/login', [CredencialescCredUsuariocClaveController::class, 'login']);
Route::post('/verificar', [MailController::class, 'index']);
Route::post('/verificar_codigo', [MailController::class, 'comparar']);
Route::post('/listar_cursos', [ListarCursosController::class, 'cursos']);

// Route::post('/login', [CredencialescCredUsuariocClaveController::class, 'login']);
// Route::post('/verificar', [MailController::class, 'index']);
// Route::post('/verificar_codigo', [MailController::class, 'comparar']);
// Route::post('/listar_cursos', [ListarCursosController::class, 'cursos']);
// Route::post('/opcion_actividades', [ActividadesAprendizajeController::class, 'crud']);

Route::get('/imprimir', PersonaController::class);

Route::post('/obtenerUsuario', [CredencialesController::class, 'obtenerUsuario']);
Route::post('/verificarUsuario', [CredencialesController::class, 'verificarUsuario']);
Route::post('/actualizarUsuario', [CredencialesController::class, 'actualizarUsuario']);

Route::group(['prefix' => 'grl'], function () {
    Route::post('listTipoIdentificaciones', [TipoIdentificacionController::class, 'list']);
    Route::post('guardarPersona', [PersonaController::class, 'save']);
    Route::post('searchPersona', [PersonaController::class, 'show']);
    Route::post('validarPersona', [PersonaController::class, 'validate']);
});