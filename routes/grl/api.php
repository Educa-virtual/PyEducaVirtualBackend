<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\grl\DiasController;
use App\Http\Controllers\grl\PersonasController;

Route::group(['prefix' => 'administracion'], function () {

  // PRIMER NIVEL
  Route::post('dias', [DiasController::class, 'list']);

});

Route::group(['prefix' => 'grl'], function () {
  Route::group(['prefix' => 'personas'], function () {
    Route::post('list', [PersonasController::class, 'list']);
  });
});