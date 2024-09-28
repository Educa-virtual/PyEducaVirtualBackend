<?php

use App\Http\Controllers\Evaluaciones\TipoPreguntaController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'evaluaciones',], function () {
    Route::group(['prefix' => 'tipo-preguntas'], function () {
        Route::get('obtenerTipoPreguntas', [TipoPreguntaController::class, 'obtenerTipoPreguntas']);
    });
});
