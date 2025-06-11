<?php

use App\Http\Controllers\api\grl\PersonaController;
use App\Http\Controllers\enc\CategoriaController;
use App\Http\Controllers\enc\EncuestaController;
use App\Http\Controllers\seg\AuthController;
use App\Http\Controllers\seg\ModuloAdministrativoController;
use App\Http\Controllers\seg\PerfilController;
use App\Http\Controllers\seg\UsuarioController;
use App\Http\Middleware\RefreshToken;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'enc', 'middleware' => ['auth:api', RefreshToken::class]], function () {
    Route::group(['prefix' => 'categorias'], function () {
        Route::get('total-encuestas', [CategoriaController::class, 'obtenerCategoriasTotalEncuestas']);
        Route::get('', [CategoriaController::class, 'obtenerCategorias']);
        Route::post('', [CategoriaController::class, 'registrarCategoria']);
        Route::group(['prefix' => '{iCategoriaEncuestaId}'], function () {
            Route::get('encuestas', [EncuestaController::class, 'obtenerEncuestasPorCategoria']);
        });
    });
});
