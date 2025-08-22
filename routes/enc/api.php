<?php

use App\Http\Controllers\enc\CategoriaController;
use App\Http\Controllers\enc\ConfiguracionEncuestaController;
use App\Http\Controllers\enc\DirectorController;
use App\Http\Controllers\enc\DocenteController;
use App\Http\Controllers\enc\EncuestaController;
use App\Http\Controllers\enc\EstudianteController;
use App\Http\Controllers\enc\TiempoDuracionController;
use App\Http\Controllers\enc\TipoAccesoController;
use App\Http\Controllers\enc\UgelController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'enc', 'middleware' => ['auth:api']], function () {
    Route::post('listarTiposAcceso', [TipoAccesoController::class, 'obtenerTiposAcceso']);
    Route::post('listarTiemposDuracion', [TiempoDuracionController::class, 'obtenerTiemposDuracion']);
    
    Route::post('listarCategorias', [CategoriaController::class, 'obtenerCategorias']);
    Route::post('guardarCategoria', [CategoriaController::class, 'registrarCategoria']);
    Route::post('verCategoria', [CategoriaController::class, 'obtenerDetallesCategoria']);
    Route::post('actualizarCategoria', [CategoriaController::class, 'actualizarCategoria']);
    Route::post('borrarCategoria', [CategoriaController::class, 'eliminarCategoria']);
    
    Route::post('listarEncuestas', [EncuestaController::class, 'listarEncuestas']);
    Route::post('crearEncuesta', [EncuestaController::class, 'crearEncuesta']);
    Route::post('verEncuesta', [EncuestaController::class, 'verEncuesta']);
    Route::post('guardarEncuesta', [EncuestaController::class, 'guardarEncuesta']);
    Route::post('actualizarEncuesta', [EncuestaController::class, 'actualizarEncuesta']);
    Route::post('borrarEncuesta', [EncuestaController::class, 'borrarEncuesta']);

    Route::post('filtrarEstudiantes', [EstudianteController::class, 'obtenerEstudiantesParaFiltroEncuesta']);
    Route::post('filtrarDocentes', [DocenteController::class, 'obtenerDocentesParaFiltroEncuesta']);
    Route::post('filtrarDirectores', [DirectorController::class, 'obtenerDirectoresParaFiltroEncuesta']);
    Route::post('filtrarUgeles', [UgelController::class, 'obtenerUgelesParaFiltroEncuesta']);
});
