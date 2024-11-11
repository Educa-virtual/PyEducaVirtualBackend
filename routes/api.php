<?php

use App\Http\Controllers\Ere\InstitucionesEducativasController;
use App\Http\Controllers\Ere\CapacidadesController;
use App\Http\Controllers\Ere\CompetenciasController;
use App\Http\Controllers\Ere\DesempenosController;

use App\Http\Controllers\api\acad\ActividadesAprendizajeController;
use App\Http\Controllers\api\acad\BibliografiaController;
use App\http\Controllers\api\acad\CalendarioAcademicosController;
use App\http\Controllers\api\acad\PeriodoAcademicosController;
use App\Http\Controllers\CredencialController;

use App\Http\Controllers\api\seg\ListarCursosController;
use App\Http\Controllers\api\acad\AutenticarUsurioController;
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
use Illuminate\Http\Request;
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

    // Route::group(['prefix' => 'Evaluaciones'], function () {
    //     Route::get('obtenerEvaluaciones', [EvaluacionesController::class, 'obtenerEvaluaciones']);

    //     Route::post('actualizar', [EvaluacionesController::class, 'actualizarEvaluacion']);

    // });
    Route::group(['prefix' => 'Evaluaciones'], function () {
        Route::get('ereObtenerEvaluacion', [EvaluacionesController::class, 'obtenerEvaluaciones']); // Cambié el nombre de la ruta para que sea más limpio

        Route::get('obtenerUltimaEvaluacion', [EvaluacionesController::class, 'obtenerUltimaEvaluacion']);
        Route::post('guardar', [EvaluacionesController::class, 'guardarEvaluacion']);

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
        //Obtener cursos
        Route::post('obtenerCursos', [EvaluacionesController::class, 'obtenerCursos']);
        //Insertar cursos
        Route::post('insertarCursos', [EvaluacionesController::class, 'insertarCursos']);
        //Ver Cursos
        // Route::get('obtenerCursosEvaluacion/{iEvaluacionId}', [EvaluacionesController::class, 'obtenerCursosEvaluacion']);
        Route::get('evaluaciones/{iEvaluacionId}/cursos', [EvaluacionesController::class, 'obtenerCursosEvaluacion']);
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
    Route::get('calendarioAcademico/selCalAcademico', [CalendarioAcademicosController::class, 'selCalAcademico']);
    Route::post('calendarioAcademico/addCalAcademico', [CalendarioAcademicosController::class, 'addCalAcademico']);
    Route::post('calendarioAcademico/searchCalAcademico', [CalendarioAcademicosController::class, 'searchCalAcademico']);
    Route::post('calendarioAcademico/addYear', [CalendarioAcademicosController::class, 'addYear']);



    Route::get('periodoAcademico/selPerAcademico', [PeriodoAcademicosController::class, 'selPerAcademico']);
    Route::post('periodoAcademico/addPerAcademico', [PeriodoAcademicosController::class, 'addPerAcademico']);
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
