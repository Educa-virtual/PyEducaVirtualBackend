<?php

use App\Http\Controllers\enc\ConfiguracionEncuestaController;
use App\Http\Controllers\enc\PublicoObjetivoController;
use App\Http\Controllers\enc\TiempoDuracionController;
use App\Http\Controllers\enc\TipoPublicoController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'enc'], function () {
  Route::group(['prefix' => 'tipo-publico'], function () {
    Route::get('/', [TipoPublicoController::class, 'listarTipoPublico']);
  });
  Route::group(['prefix' => 'tiempo-duracion'], function () {
    Route::get('/', [TiempoDuracionController::class, 'listarTiempoDuracion']);
  });
  Route::prefix('configuracion-encuesta')->group(function () {
    Route::get('/{iConfEncId}', [ConfiguracionEncuestaController::class, 'listarConfiguracionEncuesta']); // Para listar
    Route::post('/', [ConfiguracionEncuestaController::class, 'guardarConfiguracionEncuesta']); // Para crear
    Route::put('/{iConfEncId}', [ConfiguracionEncuestaController::class, 'actualizarConfiguracionEncuesta']); // Para actualizar
    Route::delete('/{iConfEncId}', [ConfiguracionEncuestaController::class, 'eliminarConfiguracionEncuesta']); // Para eliminar
  });
  Route::prefix('publico-objetivo')->group(function () {
    Route::get('/', [PublicoObjetivoController::class, 'listarPublicoObjetivo']); // Para listar
  });
});
