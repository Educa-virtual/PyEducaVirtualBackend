<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\grl\DiasController;



Route::group(['prefix' => 'administracion'], function () {

  // PRIMER NIVEL
  Route::post('dias', [DiasController::class, 'list']);

});
