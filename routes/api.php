<?php
use App\Http\Controllers\Ere\InstitucionesEducativasController;
use App\Http\Controllers\Ere\BancoPreguntasController;
use App\Http\Controllers\Ere\CapacidadesController;
use App\Http\Controllers\Ere\CompetenciasController;
use App\Http\Controllers\api\seg\sel\CredencialescCredUsuariocClaveController;
use App\Http\Controllers\api\seg\sel\ListarCursosController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\Ere\cursoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['prefix' => 'ere'], function () {

    Route::group(['prefix' => 'ie'], function () {
        Route::get('obtenerIE', [InstitucionesEducativasController::class, 'obtenerInstitucionesEducativas']);
    });

    Route::group(['prefix' => 'competencias'], function () {
        Route::get('obtenerCompetencias', [CompetenciasController::class, 'obtenerCompetencias']);
    });

    Route::group(['prefix' => 'capacidades'], function () {
        Route::get('obtenerCapacidades', [CapacidadesController::class, 'obtenerCapacidades']);
    });
    
    Route::group(['prefix' => 'banco-preguntas'], function () {
        Route::post('guardarPreguntaConAlternativas', [BancoPreguntasController::class, 'guardarPreguntaConAlternativas']);
        Route::patch('actualizarMatrizPreguntas', [BancoPreguntasController::class, 'actualizarMatrizPreguntas']);
        Route::get('obtenerBancoPreguntas', [BancoPreguntasController::class, 'obtenerBancoPreguntas']);
    });
    
    Route::group(['prefix' => 'curso'], function () {
        Route::get('obtenerCursos', [cursoController::class, 'obtenerCursos']);
    });
});
Route::post('/login', [CredencialescCredUsuariocClaveController::class, 'login']);
Route::post('/verificar', [MailController::class, 'index']);
Route::post('/verificar_codigo', [MailController::class, 'comparar']);
Route::post('/listar_cursos', [ListarCursosController::class, 'cursos']);
