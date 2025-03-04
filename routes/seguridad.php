<?php


use App\Http\Controllers\api\seg\CredencialModuloController;
use App\Http\Controllers\seg\DatabaseController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'seguridad'], function () {
  Route::group(['prefix' => 'acceso_modulos'], function () {
    Route::post('list', [CredencialModuloController::class, 'list']);
  });
  Route::group(['prefix' => 'acceso_modulos'], function () {
    Route::post('list', [CredencialModuloController::class, 'list']);
  });

  Route::group(['prefix' => 'database'], function () {
    Route::post('backups', [DatabaseController::class, 'store']);
  });
});
