<?php

use App\Http\Controllers\CredencialController;
use App\Http\Controllers\Ere\BancoPreguntasController;
use App\Http\Controllers\Ere\CompetenciasController;
use App\Http\Controllers\Ere\cursoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('/login', [CredencialController::class, 'login']);


Route::group(['prefix' => 'ere'], function () {
    
    /*Route::group(['prefix' => 'competencias'], function () {
        Route::get('getCompetenciasByAreaId', [CompetenciasController::class, 'getCompetenciasByAreaId']);
    });*/

    Route::group(['prefix' => 'banco-preguntas'], function () {
        Route::post('guardarPreguntaConAlternativas', [BancoPreguntasController::class, 'guardarPreguntaConAlternativas']);
        Route::patch('actualizarMatrizPreguntas', [BancoPreguntasController::class, 'actualizarMatrizPreguntas']);
        Route::get('obtenerBancoPreguntas', [BancoPreguntasController::class, 'obtenerBancoPreguntas']);
    });
    
    Route::group(['prefix' => 'curso'], function () {
        Route::get('obtenerCursos', [cursoController::class, 'obtenerCursos']);
    });
});
