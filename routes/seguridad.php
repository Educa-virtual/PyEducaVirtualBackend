<?php


use App\Http\Controllers\api\seg\CredencialModuloController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'seguridad'], function () {
  Route::group(['prefix' => 'acceso_modulos'], function () {
    Route::post('list', [CredencialModuloController::class, 'list']);
  });
});