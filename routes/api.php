<?php

use App\Http\Controllers\Ere\InstitucionesEducativasController;
use App\Http\Controllers\Ere\BancoPreguntasController;
use App\Http\Controllers\Ere\CapacidadesController;
use App\Http\Controllers\Ere\CompetenciasController;
use App\Http\Controllers\Ere\DesempenosController;

use App\Http\Controllers\api\acad\ActividadesAprendizajeController;
use App\Http\Controllers\api\acad\BibliografiaController;
use App\Http\Controllers\CredencialController;
use App\Http\Controllers\api\seg\sel\CredencialescCredUsuariocClaveController;
use App\Http\Controllers\api\seg\sel\ListarCursosController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\Ere\cursoController;
use App\Http\Controllers\Ere\EvaluacionesController;
use App\Http\Controllers\Ere\NivelEvaluacionController;
use App\Http\Controllers\Ere\NivelTipoController;
use App\Http\Controllers\Ere\TipoEvaluacionController;
use App\Http\Controllers\UgelesController;
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

    Route::group(['prefix' => 'banco-preguntas'], function () {
        Route::patch('actualizarMatrizPreguntas', [BancoPreguntasController::class, 'actualizarMatrizPreguntas']);
        Route::get('obtenerBancoPreguntas', [BancoPreguntasController::class, 'obtenerBancoPreguntas']);
    });

    Route::group(['prefix' => 'desempenos'], function () {
        Route::get('obtenerDesempenos', [DesempenosController::class, 'obtenerDesempenos']);
    });

    Route::group(['prefix' => 'curso'], function () {
        Route::get('obtenerCursos', [cursoController::class, 'obtenerCursos']);
    });

    Route::group(['prefix' => 'Evaluaciones'], function () {
        Route::get('obtenerEvaluaciones', [EvaluacionesController::class, 'obtenerEvaluaciones']);
    });
    Route::group(['prefix' => 'Ugeles'], function () {
        Route::get('obtenerUgeles', [UgelesController::class, 'obtenerUgeles']);
    });
});
Route::post('/login', [CredencialescCredUsuariocClaveController::class, 'login']);
Route::post('/verificar', [MailController::class, 'index']);
Route::post('/verificar_codigo', [MailController::class, 'comparar']);
Route::post('/listar_cursos', [ListarCursosController::class, 'cursos']);
// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'auth'
// ], function ($router) {
//     Route::post('/register', [AuthController::class, 'register'])->name('register');
//     Route::post('/login', [AuthController::class, 'login'])->name('login');
//     Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
//     Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
//     Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
// });

Route::post('/login', [CredencialescCredUsuariocClaveController::class, 'login']);
Route::post('/verificar', [MailController::class, 'index']);
Route::post('/verificar_codigo', [MailController::class, 'comparar']);
Route::post('/listar_cursos', [ListarCursosController::class, 'cursos']);
Route::post('/save_actividades', [ActividadesAprendizajeController::class, 'save']);
Route::post('/listar_actividades', [ActividadesAprendizajeController::class, 'list']);
Route::post('/del_actividades', [ActividadesAprendizajeController::class, 'save']);
Route::post('/upd_actividades', [ActividadesAprendizajeController::class, 'list']);
Route::post('/save_biblio', [BibliografiaController::class, 'save']);
Route::post('/listar_biblio', [BibliografiaController::class, 'list']);
Route::post('/del_biblio', [BibliografiaController::class, 'save']);
Route::post('/upd_biblio', [BibliografiaController::class, 'list']);
