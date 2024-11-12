<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\grl\DiasController;
use App\Http\Controllers\grl\PersonasContactosController;
use App\Http\Controllers\grl\PersonasController;

Route::group(['prefix' => 'administracion'], function () {

  // PRIMER NIVEL
  Route::post('dias', [DiasController::class, 'list']);

});

Route::group(['prefix' => 'grl'], function () {
  Route::group(['prefix' => 'personas'], function () {
    Route::post('list', [PersonasController::class, 'list']);
    Route::post('obtenerPersonasxiPersId', [PersonasController::class, 'obtenerPersonasxiPersId']);
    Route::post('guardarPersonasxDatosPersonales', [PersonasController::class, 'guardarPersonasxDatosPersonales']);
  });
  Route::group(['prefix' => 'personas-contactos'], function () {
    Route::post('enviarCodVerificarCorreo', [PersonasContactosController::class, 'enviarCodVerificarCorreo']);
    Route::post('verificarCodVerificarCorreo', [PersonasContactosController::class, 'verificarCodVerificarCorreo']);
  });
});