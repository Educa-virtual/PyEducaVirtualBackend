<?php

use App\Http\Controllers\bienestar\EncuestaBienestarController;
use App\Http\Controllers\bienestar\EncuestaBienestarPreguntaController;
use App\Http\Controllers\bienestar\EncuestaBienestarRespuestaController;
use App\Http\Controllers\bienestar\EncuestaBienestarResumenController;
use App\Http\Controllers\bienestar\FichaAlimentacionController;
use App\Http\Controllers\bienestar\FichaFamiliarController;
use App\Http\Controllers\bienestar\FichaGeneralController;
use App\Http\Controllers\bienestar\FichaBienestarController;
use App\Http\Controllers\bienestar\FichaRecreacionController;
use App\Http\Controllers\bienestar\FichaViviendaController;
use App\Http\Controllers\FichaEconomicoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\bienestar\FichaDiscapacidadController;
use App\Http\Controllers\bienestar\FichaPdfController;
use App\Http\Controllers\bienestar\FichaSaludController;
use App\Http\Controllers\bienestar\RecordarioFechasController;
use App\Http\Controllers\bienestar\SeguimientoBienestarController;
use App\Http\Controllers\FichaDosisController;
use App\Http\Middleware\RefreshToken;

Route::group(['prefix' => 'bienestar', 'middleware' => ['auth:api']], function () {

    Route::post('listarEstudiantesApoderado', [FichaBienestarController::class, 'listarEstudiantesApoderado']);
    Route::post('listarFichas', [FichaBienestarController::class, 'listarFichas']);
    Route::post('crearFicha', [FichaBienestarController::class, 'crearFicha']);
    Route::get('obtenerParametrosFicha', [FichaBienestarController::class, 'obtenerParametrosFicha']);
    Route::delete('borrarFicha', [FichaBienestarController::class, 'borrarFicha']);
    Route::post('verFicha', [FichaBienestarController::class, 'verFicha']);

    Route::post('descargarFicha', [FichaPdfController::class, 'descargarFicha']);

    Route::post('verFichaGeneral', [FichaGeneralController::class, 'verFichaGeneral']);
    Route::post('actualizarFichaGeneral', [FichaGeneralController::class, 'actualizarFichaGeneral']);

    /* Rutas para gestionar familiares */
    Route::post('listarFichaFamiliares', [FichaFamiliarController::class, 'listarFichaFamiliares']);
    Route::post('guardarFichaFamiliar', [FichaFamiliarController::class, 'guardarFichaFamiliar']);
    Route::post('verFichaFamiliar', [FichaFamiliarController::class, 'verFichaFamiliar']);
    Route::post('actualizarFichaFamiliar', [FichaFamiliarController::class, 'actualizarFichaFamiliar']);
    Route::post('borrarFichaFamiliar', [FichaFamiliarController::class, 'borrarFichaFamiliar']);

    /* Rutas para gestion seccion economica */
    Route::post('verFichaEconomico', [FichaEconomicoController::class, 'verFichaEconomico']);
    Route::post('guardarFichaEconomico', [FichaEconomicoController::class, 'guardarFichaEconomico']);
    Route::post('actualizarFichaEconomico', [FichaEconomicoController::class, 'actualizarFichaEconomico']);

    /* Rutas para gestion seccion vivienda */
    Route::post('verFichaVivienda', [FichaViviendaController::class, 'verFichaVivienda']);
    Route::post('guardarFichaVivienda', [FichaViviendaController::class, 'guardarFichaVivienda']);
    Route::post('actualizarFichaVivienda', [FichaViviendaController::class, 'actualizarFichaVivienda']);

    /* Rutas para gestion seccion alimentacion */
    Route::post('verFichaAlimentacion', [FichaAlimentacionController::class, 'verFichaAlimentacion']);
    Route::post('guardarFichaAlimentacion', [FichaAlimentacionController::class, 'guardarFichaAlimentacion']);
    Route::post('actualizarFichaAlimentacion', [FichaAlimentacionController::class, 'actualizarFichaAlimentacion']);

    /* Rutas para gestion seccion discapacidad */
    Route::post('verFichaDiscapacidad', [FichaDiscapacidadController::class, 'verFichaDiscapacidad']);
    Route::post('guardarFichaDiscapacidad', [FichaDiscapacidadController::class, 'guardarFichaDiscapacidad']);
    Route::post('actualizarFichaDiscapacidad', [FichaDiscapacidadController::class, 'actualizarFichaDiscapacidad']);

    /* Rutas para gestion seccion salud */
    Route::post('verFichaSalud', [FichaSaludController::class, 'verFichaSalud']);
    Route::post('guardarFichaSalud', [FichaSaludController::class, 'guardarFichaSalud']);
    Route::post('actualizarFichaSalud', [FichaSaludController::class, 'actualizarFichaSalud']);

    /* Rutas para gestion seccion recreación */
    Route::post('verFichaRecreacion', [FichaRecreacionController::class, 'verFichaRecreacion']);
    Route::post('actualizarFichaRecreacion', [FichaRecreacionController::class, 'actualizarFichaRecreacion']);

    Route::post('listarDosis', [FichaDosisController::class, 'listarDosis']);
    Route::post('verDosis', [FichaDosisController::class, 'verDosis']);
    Route::post('guardarDosis', [FichaDosisController::class, 'guardarDosis']);
    Route::post('actualizarDosis', [FichaDosisController::class, 'actualizarDosis']);
    Route::post('borrarDosis', [FichaDosisController::class, 'borrarDosis']);

    /* Recordatorios de cumpleaños */
    Route::get('verRecordatorioPeriodos', [RecordarioFechasController::class, 'verRecordatorioPeriodos']);
    Route::post('verFechasEspeciales', [RecordarioFechasController::class, 'verFechasEspeciales']);
    Route::post('verConfRecordatorio', [RecordarioFechasController::class, 'verConfRecordatorio']);
    Route::post('actualizarConfRecordatorio', [RecordarioFechasController::class, 'actualizarConfRecordatorio']);

    /* Gestionar encuestas de bienestar */
    Route::post('listarEncuestas', [EncuestaBienestarController::class, 'listarEncuestas']);
    Route::post('crearEncuesta', [EncuestaBienestarController::class, 'crearEncuesta']);
    Route::post('guardarEncuesta', [EncuestaBienestarController::class, 'guardarEncuesta']);
    Route::post('actualizarEncuesta', [EncuestaBienestarController::class, 'actualizarEncuesta']);
    Route::post('actualizarEncuestaEstado', [EncuestaBienestarController::class, 'actualizarEncuestaEstado']);
    Route::post('verEncuesta', [EncuestaBienestarController::class, 'verEncuesta']);
    Route::post('borrarEncuesta', [EncuestaBienestarController::class, 'borrarEncuesta']);

    Route::post('obtenerPoblacionObjetivo', [EncuestaBienestarController::class, 'obtenerPoblacionObjetivo']);

    Route::post('listarPreguntas', [EncuestaBienestarPreguntaController::class, 'listarPreguntas']);
    Route::post('guardarPregunta', [EncuestaBienestarPreguntaController::class, 'guardarPregunta']);
    Route::post('actualizarPregunta', [EncuestaBienestarPreguntaController::class, 'actualizarPregunta']);
    Route::post('verPregunta', [EncuestaBienestarPreguntaController::class, 'verPregunta']);
    Route::post('borrarPregunta', [EncuestaBienestarPreguntaController::class, 'borrarPregunta']);

    Route::post('listarRespuestas', [EncuestaBienestarRespuestaController::class, 'listarRespuestas']);
    Route::post('guardarRespuesta', [EncuestaBienestarRespuestaController::class, 'guardarRespuesta']);
    Route::post('actualizarRespuesta', [EncuestaBienestarRespuestaController::class, 'actualizarRespuesta']);
    Route::post('verRespuesta', [EncuestaBienestarRespuestaController::class, 'verRespuesta']);
    Route::post('borrarRespuesta', [EncuestaBienestarRespuestaController::class, 'borrarRespuesta']);
    Route::post('printRespuestas', [EncuestaBienestarRespuestaController::class, 'printRespuestas']);

    Route::post('verResumen', [EncuestaBienestarResumenController::class, 'verResumen']);

    /* Gestionar reporte de fichas de bienestar */
    Route::post('crearReporte', [FichaBienestarController::class, 'crearReporte']);
    Route::post('verReporte', [FichaBienestarController::class, 'verReporte']);

    /* Gestionar fichas de seguimiento */
    Route::post('crearSeguimiento', [SeguimientoBienestarController::class, 'crearSeguimiento']);
    Route::post('verSeguimientos', [SeguimientoBienestarController::class, 'verSeguimientos']);
    Route::post('verSeguimientosPersona', [SeguimientoBienestarController::class, 'verSeguimientosPersona']);
    Route::post('guardarSeguimiento', [SeguimientoBienestarController::class, 'guardarSeguimiento']);
    Route::post('actualizarSeguimiento', [SeguimientoBienestarController::class, 'actualizarSeguimiento']);
    Route::post('verSeguimiento', [SeguimientoBienestarController::class, 'verSeguimiento']);
    Route::post('borrarSeguimiento', [SeguimientoBienestarController::class, 'borrarSeguimiento']);
    Route::post('verDatosPersona', [SeguimientoBienestarController::class, 'verDatosPersona']);
    Route::post('descargarSeguimiento', [SeguimientoBienestarController::class, 'descargarSeguimiento']);
});

