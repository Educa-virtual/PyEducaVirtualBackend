<?php

use App\Http\Controllers\acad\Curriculas;
use App\Http\Controllers\acad\Cursos;
use App\Http\Controllers\acad\DetalleEvaluaciones;
use App\Http\Controllers\acad\DocenteCursos;
use App\Http\Controllers\acad\RecursoDidacticos;
use App\Http\Controllers\acad\RecursoSilabos;
use App\Http\Controllers\acad\SilaboMetodologias;
use App\Http\Controllers\acad\Silabos;
use App\Http\Controllers\acad\TipoBibliografias;
use App\Http\Controllers\acad\TipoMetodologias;
use App\Http\Controllers\acad\BibliografiaController;
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
    Route::post('store', [SilaboMetodologias::class, 'store']);
  });
  Route::group(['prefix' => 'recurso-didactivos'], function () {
    Route::post('list', [RecursoDidacticos::class, 'list']);
  });
  Route::group(['prefix' => 'recurso-silabos'], function () {
    Route::post('list', [RecursoSilabos::class, 'list']);
    Route::post('store', [RecursoSilabos::class, 'store']);
  });
  Route::group(['prefix' => 'detalle-evaluaciones'], function () {
    Route::post('list', [DetalleEvaluaciones::class, 'list']);
    Route::post('store', [DetalleEvaluaciones::class, 'store']);
  });
  Route::group(['prefix' => 'bibliografias'], function () {
    // Route::post('/save_biblio', [BibliografiaController::class, 'save']);
    // Route::post('/listar_biblio', [BibliografiaController::class, 'list']);
    // Route::post('/del_biblio', [BibliografiaController::class, 'save']);

    Route::post('/list', [BibliografiaController::class, 'list']);
    Route::post('/store', [BibliografiaController::class, 'store']);
  });
  Route::group(['prefix' => 'tipo-bibliografias'], function () {
    Route::post('list', [TipoBibliografias::class, 'list']);
  });
});
