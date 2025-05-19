<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seg\CredencialesController;

Route::group(['prefix' => 'seg'], function () {
  Route::group(['prefix' => 'credenciales'], function () {
    Route::post('updatePassword', [CredencialesController::class, 'updatePassword']);
  });
});
