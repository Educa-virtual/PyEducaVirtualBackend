<?php

use App\Http\Controllers\acad\ApoderadoController;
use App\Http\Middleware\RefreshToken;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'apo', 'middleware' => ['auth:api', RefreshToken::class]], function () {
    Route::post('buscarPersonaApoderado', [ApoderadoController::class, 'buscarPersonaApoderado']);
    Route::post('listarApoderados', [ApoderadoController::class, 'listarApoderados']);
    Route::post('verApoderado', [ApoderadoController::class, 'verApoderado']);
    Route::post('guardarApoderado', [ApoderadoController::class, 'guardarApoderado']);
    Route::post('actualizarApoderado', [ApoderadoController::class, 'actualizarApoderado']);
    Route::post('actualizarApoderadoEstado', [ApoderadoController::class, 'actualizarApoderadoEstado']);
    Route::post('borrarApoderado', [ApoderadoController::class, 'borrarApoderado']);
});
