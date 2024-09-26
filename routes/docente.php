<?php

use App\Http\Controllers\DOC\Curriculas;
use App\Http\Controllers\DOC\Cursos;
use App\Http\Controllers\DOC\DocenteCursos;
use App\Http\Controllers\DOC\SilaboMetodologias;
use App\Http\Controllers\DOC\Silabos;
use App\Http\Controllers\DOC\TipoMetodologias;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'docente'], function () {
  Route::group(['prefix' => 'docente-cursos'], function () {
    Route::post('list', [DocenteCursos::class, 'list']);
  });
  Route::group(['prefix' => 'curriculas'], function () {
    Route::post('list', [Curriculas::class, 'list']);
  });
  Route::group(['prefix' => 'cursos'], function () {
    Route::post('list', [Cursos::class, 'list']);
  });
  Route::group(['prefix' => 'silabos'], function () {
    Route::post('list', [Silabos::class, 'list']);
  });
  Route::group(['prefix' => 'tipo-metodologias'], function () {
    Route::post('list', [TipoMetodologias::class, 'list']);
  });
  Route::group(['prefix' => 'silabo-metodologias'], function () {
    Route::post('list', [SilaboMetodologias::class, 'list']);
  });
});
