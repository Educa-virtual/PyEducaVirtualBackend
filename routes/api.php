<?php

use App\Http\Controllers\CredencialController;
use App\Http\Controllers\Ere\BancoPreguntasController;
use App\Http\Controllers\Ere\CapacidadesController;
use App\Http\Controllers\Ere\CompetenciasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('/login', [CredencialController::class, 'login']);


Route::group(['prefix' => 'ere'], function () {
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
});
