<?php

use App\Http\Controllers\DOC\Curriculas;
use App\Http\Controllers\DOC\Cursos;
use App\Http\Controllers\DOC\DocenteCursos;
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
});
