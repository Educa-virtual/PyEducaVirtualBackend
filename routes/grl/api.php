<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\grl\DiasController;
use App\Http\Controllers\grl\FeriadosNacionalesController;
use App\Http\Controllers\grl\PersonasContactosController;
use App\Http\Controllers\grl\PersonasController;
use App\Http\Controllers\grl\PrioridadController;
use App\Http\Controllers\grl\YearController;

Route::group(['prefix' => 'administracion'], function () {

  // PRIMER NIVEL
  Route::post('dias', [DiasController::class, 'list']);
});

Route::group(['prefix' => 'grl'], function () {
  Route::get('prioridades', [PrioridadController::class, 'obtenerPrioridades']);
  Route::group(['prefix' => 'personas'], function () {
    Route::post('list', [PersonasController::class, 'list']);
    Route::post('obtenerPersonasxiPersId', [PersonasController::class, 'obtenerPersonasxiPersId']);
    Route::post('guardarPersonasxDatosPersonales', [PersonasController::class, 'guardarPersonasxDatosPersonales']);
  });
  Route::group(['prefix' => 'personas-contactos'], function () {
    Route::post('enviarCodVerificarCorreo', [PersonasContactosController::class, 'enviarCodVerificarCorreo']);
    Route::post('verificarCodVerificarCorreo', [PersonasContactosController::class, 'verificarCodVerificarCorreo']);
  });
  Route::group(['prefix' => 'feriados-nacionales'], function () {
    Route::get('getFeriadosNacionales/{iYearId?}', [FeriadosNacionalesController::class, 'getFeriadosNacionales']);
    Route::post('insFeriadosNacionales', [FeriadosNacionalesController::class, 'insFeriadosNacionales']);
    Route::post('insFeriadosNacionalesMasivo', [FeriadosNacionalesController::class, 'insFeriadosNacionalesMasivo']);
    Route::put('updFeriadosNacionales', [FeriadosNacionalesController::class, 'updFeriadosNacionales']);
    Route::put('syncFeriadosNacionales', [FeriadosNacionalesController::class, 'syncFeriadosNacionales']);
    Route::delete('deleteFeriadosNacionales/{iFeriadoId}', [FeriadosNacionalesController::class, 'deleteFeriadosNacionales']);
  });
  Route::group(['prefix' => 'years'], function () {
    Route::get('getYears/{iYearId?}/', [YearController::class, 'getYears']);
    Route::post('insYears', [YearController::class, 'insYears']);
    Route::put('updYears', [YearController::class, 'updYears']);
    Route::delete('deleteYears/{iYearId}', [YearController::class, 'deleteYears']);
  });
});
