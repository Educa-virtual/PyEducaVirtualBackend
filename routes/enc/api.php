<?php

use App\Http\Controllers\enc\CategoriaController;
use App\Http\Controllers\enc\EncuestaController;
use App\Http\Controllers\enc\PreguntaController;
use App\Http\Controllers\enc\SeccionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'enc', 'middleware' => ['auth:api']], function () {
    Route::post('listarCategorias', [CategoriaController::class, 'listarCategorias']);
    Route::post('guardarCategoria', [CategoriaController::class, 'guardarCategoria']);
    Route::post('verCategoria', [CategoriaController::class, 'verCategoria']);
    Route::post('actualizarCategoria', [CategoriaController::class, 'actualizarCategoria']);
    Route::post('borrarCategoria', [CategoriaController::class, 'borrarCategoria']);

    Route::post('obtenerPoblacionObjetivo', [EncuestaController::class, 'obtenerPoblacionObjetivo']);
    Route::post('listarEncuestas', [EncuestaController::class, 'listarEncuestas']);
    Route::post('crearEncuesta', [EncuestaController::class, 'crearEncuesta']);
    Route::post('verEncuesta', [EncuestaController::class, 'verEncuesta']);
    Route::post('guardarEncuesta', [EncuestaController::class, 'guardarEncuesta']);
    Route::post('actualizarEncuesta', [EncuestaController::class, 'actualizarEncuesta']);
    Route::post('borrarEncuesta', [EncuestaController::class, 'borrarEncuesta']);
    Route::post('actualizarEncuestaEstado', [EncuestaController::class, 'actualizarEncuestaEstado']);

    Route::post('listarSecciones', [SeccionController::class, 'listarSecciones']);
    Route::post('verSeccion', [SeccionController::class, 'verSeccion']);
    Route::post('guardarSeccion', [SeccionController::class, 'guardarSeccion']);
    Route::post('actualizarSeccion', [SeccionController::class, 'actualizarSeccion']);
    Route::post('borrarSeccion', [SeccionController::class, 'borrarSeccion']);

    Route::post('listarPreguntas', [PreguntaController::class, 'listarPreguntas']);
    Route::post('verPregunta', [PreguntaController::class, 'verPregunta']);
    Route::post('guardarPregunta', [PreguntaController::class, 'guardarPregunta']);
    Route::post('actualizarPregunta', [PreguntaController::class, 'actualizarPregunta']);
    Route::post('borrarPregunta', [PreguntaController::class, 'borrarPregunta']);
});
