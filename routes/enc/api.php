<?php

use App\Http\Controllers\api\grl\PersonaController;
use App\Http\Controllers\enc\CategoriaController;
use App\Http\Controllers\enc\DirectorController;
use App\Http\Controllers\enc\DocenteController;
use App\Http\Controllers\enc\EncuestaController;
use App\Http\Controllers\enc\EstudianteController;
use App\Http\Controllers\enc\UgelController;
use App\Http\Controllers\seg\AuthController;
use App\Http\Controllers\seg\ModuloAdministrativoController;
use App\Http\Controllers\seg\PerfilController;
use App\Http\Controllers\seg\UsuarioController;
use App\Http\Middleware\RefreshToken;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'enc', 'middleware' => ['auth:api', RefreshToken::class]], function () {
    Route::group(['prefix' => 'categorias'], function () {
        Route::get('', [CategoriaController::class, 'obtenerCategorias']);
        Route::post('', [CategoriaController::class, 'registrarCategoria']);
        Route::patch('', [CategoriaController::class, 'actualizarCategoria']);
        Route::delete('', [CategoriaController::class, 'eliminarCategoria']);

        Route::group(['prefix' => '{iCategoriaEncuestaId}'], function () {
            Route::get('encuestas', [EncuestaController::class, 'obtenerEncuestasPorCategoria']);
        });
    });

    Route::get('ugeles', [UgelController::class, 'obtenerUgeles']);


    Route::group(['prefix' => 'encuestas'], function () {
        Route::group(['prefix' => 'filtros'], function () {
            Route::get('estudiantes', [EstudianteController::class, 'obtenerEstudiantesParaFiltroEncuesta']);
            Route::get('docentes', [DocenteController::class, 'obtenerDocentesParaFiltroEncuesta']);
            Route::get('directores', [DirectorController::class, 'obtenerDirectoresParaFiltroEncuesta']);
            Route::get('ugeles', [UgelController::class, 'obtenerUgelesParaFiltroEncuesta']);
        });
    });

});
