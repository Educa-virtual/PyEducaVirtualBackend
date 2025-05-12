<?php

use App\Http\Controllers\acad\ApoderadoController;
use App\Http\Controllers\acad\EstudiantesController;
use App\Http\Controllers\acad\GradosController;
use App\Http\Controllers\acad\MatriculaController;
use App\Http\Controllers\acad\AdministradorController;
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
use App\Http\Controllers\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::group(['prefix' => 'administrador'], function () {
    Route::post('addCurriculas', [administradorController::class, 'addCurriculas']);
    Route::post('mensaje', [administradorController::class, 'mensaje']);
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
        Route::post('generarCredencialesIE', [GestionInstitucionalController::class, 'generarCredencialesIE']);
    });
 Route::post('generarConfiguracionMasivaInicio', [CalendarioAcademicosController::class, 'generarConfiguracionMasivaInicio']); // procedimiento masivo para generar configuraciones de inicio escolar
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

        Route::post('generarConfiguracionMasivaInicio', [CalendarioAcademicosController::class, 'generarConfiguracionMasivaInicio']); // procedimiento masivo para generar configuraciones de inicio escolar

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

        Route::post('importarEstudiantesMatriculasExcelPlatform', [FileController::class, 'importarEstudiantesMatriculasExcel']);
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

Route::group(['prefix' => 'enlaces-ayuda'], function () {
    Route::get('obtenerEnlaces', function () {
        $path = 'enlaces-ayuda.json'; // Nombre del archivo dentro de storage/app/public/
    
        if (Storage::disk('public')->exists($path)) {
            return Response::json(json_decode(Storage::disk('public')->get($path)), 200);
        }
    
        return response()->json(['error' => 'Archivo no encontrado'], 404);
    });
    
    Route::post('actualizarEnlaces', function (Request $request, $index) {
        $path = 'data/miarchivo.json';
    
        if (!Storage::exists($path)) {
            return response()->json(['error' => 'Archivo no encontrado'], 404);
        }
    
        // Obtener el contenido del JSON
        $data = json_decode(Storage::get($path), true);
    
        // Verificar si el índice existe
        if (!isset($data[$index])) {
            return response()->json(['error' => 'Elemento no encontrado'], 404);
        }
    
        // Actualizar los datos
        $data[$index] = array_merge($data[$index], $request->all());
    
        // Guardar el JSON actualizado
        Storage::put($path, json_encode($data, JSON_PRETTY_PRINT));
    
        return response()->json(['message' => 'Datos actualizados correctamente', 'data' => $data[$index]], 200);
    });

});

