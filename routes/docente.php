<?php

use App\Http\Controllers\acad\CurriculasController;
use App\Http\Controllers\acad\CursosController;
use App\Http\Controllers\acad\DetalleEvaluacionesController;
use App\Http\Controllers\acad\DocenteCursosController;
use App\Http\Controllers\acad\RecursoDidacticosController;
use App\Http\Controllers\acad\RecursoSilabosController;
use App\Http\Controllers\acad\SilaboMetodologiasController;
use App\Http\Controllers\acad\SilabosController;
use App\Http\Controllers\acad\TipoBibliografiasController;
use App\Http\Controllers\acad\TipoMetodologiasController;
use App\Http\Controllers\acad\BibliografiaController;
use App\Http\Controllers\api\acad\EstudiantesController;
use App\Http\Controllers\acad\ContenidoSemanasController;
use App\Http\Controllers\acad\FechasImportantesController;
use App\Http\Controllers\acad\IndicadorActividadesController;
use App\Http\Controllers\DOC\MaterialEducativosController;
use App\Http\Controllers\acad\SilaboActividadAprendizajesController;
use App\Http\Controllers\acad\TipoIndicadorLogrosController;
use App\Http\Controllers\api\acad\GradoAcademicosController;
use App\Http\Controllers\api\asi\AsistenciaController;
use App\Http\Controllers\api\grl\PersonaController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'docente'], function () {
  Route::group(['prefix' => 'docente-cursos'], function () {
    Route::post('list', [DocenteCursosController::class, 'list']);
  });
  Route::group(['prefix' => 'curriculas'], function () {
    Route::post('list', [CurriculasController::class, 'list']);
  });
  Route::group(['prefix' => 'cursos'], function () {
    Route::post('list', [CursosController::class, 'list']);
  });
  Route::group(['prefix' => 'silabos'], function () {
    Route::post('list', [SilabosController::class, 'list']);
  });
  Route::group(['prefix' => 'tipo-metodologias'], function () {
    Route::post('list', [TipoMetodologiasController::class, 'list']);
  });
  Route::group(['prefix' => 'silabo-metodologias'], function () {
    Route::post('list', [SilaboMetodologiasController::class, 'list']);
    Route::post('store', [SilaboMetodologiasController::class, 'store']);
  });
  Route::group(['prefix' => 'recurso-didactivos'], function () {
    Route::post('list', [RecursoDidacticosController::class, 'list']);
  });
  Route::group(['prefix' => 'recurso-silabos'], function () {
    Route::post('list', [RecursoSilabosController::class, 'list']);
    Route::post('store', [RecursoSilabosController::class, 'store']);
  });
  Route::group(['prefix' => 'detalle-evaluaciones'], function () {
    Route::post('list', [DetalleEvaluacionesController::class, 'list']);
    Route::post('store', [DetalleEvaluacionesController::class, 'store']);
  });
  Route::group(['prefix' => 'bibliografias'], function () {
    Route::post('/list', [BibliografiaController::class, 'list']);
    Route::post('/store', [BibliografiaController::class, 'store']);
  });
  Route::group(['prefix' => 'tipo-bibliografias'], function () {
    Route::post('list', [TipoBibliografiasController::class, 'list']);
  });
  Route::group(['prefix' => 'silabo-actividad-aprendizajes'], function () {
    Route::post('list', [SilaboActividadAprendizajesController::class, 'list']);
    Route::post('store', [SilaboActividadAprendizajesController::class, 'store']);
  });
  Route::group(['prefix' => 'indicador-actividades'], function () {
    Route::post('list', [IndicadorActividadesController::class, 'list']);
    Route::post('store', [IndicadorActividadesController::class, 'store']);
  });
  Route::group(['prefix' => 'tipo-indicador-logros'], function () {
    Route::post('list', [TipoIndicadorLogrosController::class, 'list']);
  });
  Route::group(['prefix' => 'contenido-semanas'], function () {
    Route::post('list', [ContenidoSemanasController::class, 'list']);
    Route::post('store', [ContenidoSemanasController::class, 'store']);
  });
  Route::group(['prefix' => 'grado-academico'], function () {
    Route::post('list', [GradoAcademicosController::class, 'list']);
  });
  Route::group(['prefix' => 'persona'], function () {
    Route::post('list', [PersonaController::class, 'list']);
  });
  Route::group(['prefix' => 'asistencia'], function () {
    Route::post('list', [AsistenciaController::class, 'list']);
  });
  Route::group(['prefix' => 'estudiante'], function () {
    Route::post('list', [EstudiantesController::class, 'list']);
  });
  Route::group(['prefix' => 'silabus_reporte'], function () {
    Route::post('report', [SilabosController::class, 'report']);
  });
  Route::group(['prefix' => 'fechas_importantes'], function () {
    Route::post('list', [FechasImportantesController::class, 'list']);
  });
  Route::group(['prefix' => 'reporte_mensual'], function () {
    Route::get('report/{tipoReporte}', [AsistenciaController::class, 'report']);
  });
  Route::group(['prefix' => 'material-educativos'], function () {
    Route::post('list', [MaterialEducativosController::class, 'list']);
    Route::post('store', [MaterialEducativosController::class, 'store']);
    Route::post('update', [MaterialEducativosController::class, 'update']);
    Route::post('delete', [MaterialEducativosController::class, 'delete']);
  });
});
