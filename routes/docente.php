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
use App\Http\Controllers\acad\BuscarCurriculaController;
use App\Http\Controllers\acad\ContenidoSemanasController;
use App\Http\Controllers\acad\FechasImportantesController;
use App\Http\Controllers\acad\IndicadorActividadesController;
use App\Http\Controllers\doc\MaterialEducativosController;
use App\Http\Controllers\acad\SilaboActividadAprendizajesController;
use App\Http\Controllers\acad\TipoIndicadorLogrosController;
use App\Http\Controllers\api\acad\GradoAcademicosController;
use App\Http\Controllers\asi\AsistenciaController;
use App\Http\Controllers\api\grl\PersonaController;
use App\Http\Controllers\doc\CargaNoLectivasController;
use App\Http\Controllers\doc\CuadernosCampoController;
use App\Http\Controllers\doc\DetalleCargaNoLectivasController;
use App\Http\Controllers\doc\PortafoliosController;
use App\Http\Controllers\doc\TiposCargaNoLectivasController;
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
    Route::post('store', [TipoBibliografiasController::class, 'store']);
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
    //Route::post('list', [AsistenciaController::class, 'list']);
    Route::post('guardarAsistencia', [AsistenciaController::class, 'guardarAsistencia']);
    Route::post('obtenerEstudiante', [AsistenciaController::class, 'obtenerEstudiante']);
    Route::post('obtenerFestividad', [AsistenciaController::class, 'obtenerFestividad']);
  });
  // Route::group(['prefix' => 'estudiante'], function () {
  //   Route::post('list', [EstudiantesController::class, 'list']);
  // });
  Route::group(['prefix' => 'silabus_reporte'], function () {
    Route::post('report', [SilabosController::class, 'report']);
  });
  Route::group(['prefix' => 'reporte_asistencia'], function () {
    Route::post('obtenerCursoHorario', [AsistenciaController::class, 'obtenerCursoHorario']);
    Route::post('obtenerAsistencia', [AsistenciaController::class, 'obtenerAsistencia']);
    Route::post('reporte_mensual', [AsistenciaController::class, 'report']);
    Route::post('reporte_personalizado', [AsistenciaController::class, 'reporte_personalizado']);
    Route::post('reporte_asistencia_general', [AsistenciaController::class, 'reporteAsistenciaGeneral']);
    Route::post('reporte_diario', [AsistenciaController::class, 'reporte_diario']);
    //Route::get('reportAExcel/{tipoReporte}', [AsistenciaController::class, 'reportToExcel']);
  });
  Route::group(['prefix' => 'material-educativos'], function () {
    Route::post('list', [MaterialEducativosController::class, 'list']);
    Route::post('store', [MaterialEducativosController::class, 'store']);
    Route::post('update', [MaterialEducativosController::class, 'update']);
    Route::post('delete', [MaterialEducativosController::class, 'delete']);
  });
  Route::group(['prefix' => 'tipos-carga-no-lectivas'], function () {
    Route::post('list', [TiposCargaNoLectivasController::class, 'list']);
  });
  Route::group(['prefix' => 'detalle-carga-no-lectivas'], function () {
    Route::post('list', [DetalleCargaNoLectivasController::class, 'list']);
    Route::post('store', [DetalleCargaNoLectivasController::class, 'store']);
    Route::post('update', [DetalleCargaNoLectivasController::class, 'update']);
    Route::post('delete', [DetalleCargaNoLectivasController::class, 'delete']);
  });
  Route::group(['prefix' => 'carga-no-lectivas'], function () {
    Route::post('list', [CargaNoLectivasController::class, 'list']);
    Route::post('store', [CargaNoLectivasController::class, 'store']);
    Route::post('update', [CargaNoLectivasController::class, 'update']);
    Route::post('delete', [CargaNoLectivasController::class, 'delete']);
  });
  Route::group(['prefix' => 'portafolios'], function () {
    Route::post('obtenerPortafolios', [PortafoliosController::class, 'obtenerPortafolios']);
    Route::post('guardarItinerario', [PortafoliosController::class, 'guardarItinerario']);
  });
  Route::group(['prefix' => 'cuadernos-campo'], function () {
    Route::post('obtenerCuadernosCampo', [CuadernosCampoController::class, 'obtenerCuadernosCampo']);
    Route::post('guardarFichasCuadernosCampo', [CuadernosCampoController::class, 'guardarFichasCuadernosCampo']);
  });
  Route::group(['prefix' => 'buscar_curso'], function () {
    Route::post('curricula', [BuscarCurriculaController::class, 'curricula']);
    Route::post('curriculaHorario', [BuscarCurriculaController::class, 'curriculaHorario']);
    Route::post('obtenerActividad', [BuscarCurriculaController::class, 'obtenerActividad']);
  });
});
