<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\grl\DiasController;
use App\Http\Controllers\grl\FeriadosNacionalesController;
use App\Http\Controllers\grl\PersonasContactosController;
use App\Http\Controllers\grl\PersonasController;
use App\Http\Controllers\grl\PrioridadController;
use App\Http\Controllers\grl\TipoIdentificacionController;
use App\Http\Controllers\grl\YearController;

Route::group(['prefix' => 'administracion'], function () {

  // PRIMER NIVEL
  Route::post('dias', [DiasController::class, 'list']);
});

Route::group(['prefix' => 'grl', 'middleware' => ['auth:api']], function () {
  Route::post('selTipoIdentificacion', [TipoIdentificacionController::class, 'selTipoIdentificacion']);
  
  Route::get('prioridades', [PrioridadController::class, 'obtenerPrioridades']);
  Route::group(['prefix' => 'personas'], function () {
    Route::post('list', [PersonasController::class, 'list']);
    Route::patch('datos-personales', [PersonasController::class, 'actualizarDatosPersonales'])->middleware('auth:api');
    Route::post('foto-perfil', [PersonasController::class, 'actualizarFotoPerfil'])->middleware('auth:api');
    Route::get('obtenerPersonasxiPersId', [PersonasController::class, 'obtenerPersonasxiPersId']);
    Route::post('guardarPersonasxDatosPersonales', [PersonasController::class, 'guardarPersonasxDatosPersonales']);
  });
  Route::group(['prefix' => 'personas-contactos'], function () {
    Route::post('enviarCodVerificarCorreo', [PersonasContactosController::class, 'enviarCodVerificarCorreo']);
    Route::post('verificarCodVerificarCorreo', [PersonasContactosController::class, 'verificarCodVerificarCorreo']);
  });
  Route::group(['prefix' => 'feriados-nacionales'], function () {
    Route::post('listarFeriadosNacionales', [FeriadosNacionalesController::class, 'listarFeriadosNacionales']);
    Route::post('guardarFeriadoNacional', [FeriadosNacionalesController::class, 'guardarFeriadoNacional']);
    Route::post('guardarFeriadoNacionalMasivo', [FeriadosNacionalesController::class, 'guardarFeriadoNacionalMasivo']);
    Route::post('actualizarFeriadoNacional', [FeriadosNacionalesController::class, 'actualizarFeriadoNacional']);
    Route::post('aplicarFeriadosNacionales', [FeriadosNacionalesController::class, 'aplicarFeriadosNacionales']);
    Route::post('borrarFeriadoNacional', [FeriadosNacionalesController::class, 'borrarFeriadoNacional']);
  });
  Route::group(['prefix' => 'years'], function () {
    Route::get('getYears/{iYearId?}/', [YearController::class, 'getYears']);
    Route::post('insYears', [YearController::class, 'insYears']);
    Route::put('updYears', [YearController::class, 'updYears']);
    Route::delete('deleteYears/{iYearId}', [YearController::class, 'deleteYears']);
  });


});
