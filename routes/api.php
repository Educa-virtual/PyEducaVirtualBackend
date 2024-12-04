<?php

use App\Http\Controllers\Ere\InstitucionesEducativasController;
use App\Http\Controllers\Ere\CapacidadesController;
use App\Http\Controllers\Ere\CompetenciasController;
use App\Http\Controllers\Ere\DesempenosController;

use App\Http\Controllers\api\acad\ActividadesAprendizajeController;
use App\Http\Controllers\api\acad\BibliografiaController;
use App\http\Controllers\api\acad\CalendarioAcademicosController;
use App\http\Controllers\api\acad\GestionInstitucionalController;
use App\http\Controllers\api\acad\PeriodoAcademicosController;
use App\Http\Controllers\CredencialController;

use App\Http\Controllers\api\seg\ListarCursosController;
use App\Http\Controllers\api\acad\AutenticarUsurioController;
use App\Http\Controllers\api\acad\InstitucionesEducativasController as AcadInstitucionesEducativasController;
use App\Http\Controllers\api\grl\PersonaController;
use App\Http\Controllers\api\acad\SelectPerfilesController;
use App\Http\Controllers\api\seg\CredencialescCredUsuariocClaveController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\Ere\cursoController;
use App\Http\Controllers\Ere\EvaluacionesController;
use App\Http\Controllers\Ere\NivelEvaluacionController;
use App\Http\Controllers\Ere\NivelTipoController;
use App\Http\Controllers\Ere\TipoEvaluacionController;
use App\Http\Controllers\Ere\UgelesController;
use App\Http\Controllers\seg\AuditoriaController;
use App\Http\Controllers\seg\CredencialesController;
use Illuminate\Support\Facades\Route;

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
        //!Agregando participacion y eliminando participacion, IE
        Route::post('guardarParticipacion', [EvaluacionesController::class, 'guardarParticipacion']);
        Route::delete('eliminarParticipacion', [EvaluacionesController::class, 'eliminarParticipacion']);
        //Agregando participacion nuevo
        Route::post('guardarParticipacionNuevo', [EvaluacionesController::class, 'guardarParticipacionNuevo']);
        //! Ruta para actualizar la evaluación
        Route::put('actualizar/{iEvaluacionId}', [EvaluacionesController::class, 'actualizarEvaluacion']);
        //! Ruta para obtener las participaciones
        Route::get('obtenerParticipaciones', [EvaluacionesController::class, 'obtenerParticipaciones']);
        //Nuevo Ver con Datos completos
        Route::get('verParticipacionNuevo', [EvaluacionesController::class, 'verParticipacionNuevo']);
        //Obtener Cursos
        Route::post('obtenerCursos', [EvaluacionesController::class, 'obtenerCursos']);
        //Insertar Cursos
        Route::post('insertarCursos', [EvaluacionesController::class, 'insertarCursos']);
        //!Eliminar Cursos
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
        //!Agregando CopiarEvaluacion
        Route::post('copiarEvaluacion', [EvaluacionesController::class, 'copiarEvaluacion']);
        //!ObtenerMatrizCompetencia
        Route::get('obtenerMatrizCompetencias', [EvaluacionesController::class, 'obtenerMatrizCompetencias']);
        //!ObtenerMatrizCapacidad
        Route::get('obtenerMatrizCapacidades', [EvaluacionesController::class, 'obtenerMatrizCapacidades']);
        //!InsertarMatrizDesempeno
        Route::post('insertarMatrizDesempeno', [EvaluacionesController::class, 'insertarMatrizDesempeno']);
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
/*
    Route::group(['prefix' => 'gestionInstitucional'], function () {
        Route::post('listarPersonalIes', [GestionInstitucionalController::class, 'listarPersonalIes']);
        //procendimiento generales
        Route::post('insertMaestroDetalle', [GestionInstitucionalController::class, 'insertMaestroDetalle']);
        Route::post('insertMaestro', [GestionInstitucionalController::class, 'insertMaestro']);
        Route::post('updateMaestro', [GestionInstitucionalController::class, 'updateMaestro']);
        Route::post('deleteMaestro', [GestionInstitucionalController::class, 'deleteMaestro']);
    });*/

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

        Route::post('addAmbiente', [CalendarioAcademicosController::class, 'addAmbienteAcademico']);
        Route::post('searchAmbiente', [CalendarioAcademicosController::class, 'selAmbienteAcademico']);
        Route::post('searchGradoCiclo', [CalendarioAcademicosController::class, 'searchGradoCiclo']);
        

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
});

Route::group(['prefix' => 'seg'], function () {
    Route::group(['prefix' => 'auditoria'], function () {
        Route::get('sel_auditoria_accesos', [AuditoriaController::class, 'sel_auditoria_accesos']);
        Route::get('sel_auditoria_accesos_fallidos', [AuditoriaController::class, 'sel_auditoria_accesos_fallidos']);
        Route::get('sel_auditoria', [AuditoriaController::class, 'sel_auditoria']);
        Route::get('sel_auditoria_backend', [AuditoriaController::class, 'sel_auditoria_backend']);
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
