<?php

use App\Http\Controllers\bienestar\EncuestaBienestarController;
use App\Http\Controllers\bienestar\EncuestaBienestarPreguntaController;
use App\Http\Controllers\bienestar\EncuestaBienestarRespuestaController;
use App\Http\Controllers\bienestar\FichaAlimentacionController;
use App\Http\Controllers\bienestar\FichaFamiliarController;
use App\Http\Controllers\bienestar\FichaGeneralController;
use App\Http\Controllers\bienestar\FichaBienestarController;
use App\Http\Controllers\bienestar\FichaRecreacionController;
use App\Http\Controllers\bienestar\FichaViviendaController;
use App\Http\Controllers\FichaEconomicoController;
use Illuminate\Support\Facades\Route;
//Se agrego codigo el 22 Abril--------
use app\Http\Controllers\bienestar\EstudianteController;
use App\Http\Controllers\bienestar\FichaDiscapacidadController;
use App\Http\Controllers\bienestar\FichaPdfController;
use App\Http\Controllers\bienestar\FichaSaludController;

Route::group(['prefix' => 'bienestar'], function () {

    Route::get('searchFichasEstudiantes', [FichaGeneralController::class, 'indexEstudiantes']);

    Route::post('searchFichas', [FichaBienestarController::class, 'listarFichas']);
    Route::get('createFicha', [FichaBienestarController::class, 'crearFicha']);
    Route::delete('deleteFicha', [FichaBienestarController::class, 'borrarFicha']);
    Route::post('searchFicha', [FichaBienestarController::class, 'verFicha']);

    Route::post('searchFichaGeneral', [FichaGeneralController::class, 'verFichaGeneral']);
    Route::post('guardarFichaGeneral', [FichaGeneralController::class, 'guardarFichaGeneral']);
    Route::post('actualizarFichaGeneral', [FichaGeneralController::class, 'actualizarFichaGeneral']);

    /* Rutas para gestionar familiares */
    Route::post('searchFichaFamiliares', [FichaFamiliarController::class, 'listarFichaFamiliares']);
    Route::post('guardarFichaFamiliar', [FichaFamiliarController::class, 'guardarFichaFamiliar']);
    Route::post('searchFichaFamiliar', [FichaFamiliarController::class, 'verFichaFamiliar']);
    Route::post('actualizarFichaFamiliar', [FichaFamiliarController::class, 'actualizarFichaFamiliar']);
    Route::post('borrarFichaFamiliar', [FichaFamiliarController::class, 'borrarFichaFamiliar']);

    /* Rutas para gestion seccion economica */
    Route::post('searchFichaEconomico', [FichaEconomicoController::class, 'verFichaEconomico']);
    Route::post('guardarFichaEconomico', [FichaEconomicoController::class, 'guardarFichaEconomico']);
    Route::post('actualizarFichaEconomico', [FichaEconomicoController::class, 'actualizarFichaEconomico']);

    /* Rutas para gestion seccion vivienda */
    Route::post('verFichaVivienda', [FichaViviendaController::class, 'verFichaVivienda']);
    Route::post('guardarFichaVivienda', [FichaViviendaController::class, 'guardarFichaVivienda']);
    Route::post('actualizarFichaVivienda', [FichaViviendaController::class, 'actualizarFichaVivienda']);

    /* Rutas para gestion seccion alimentacion */
    Route::post('searchFichaAlimentacion', [FichaAlimentacionController::class, 'verFichaAlimentacion']);
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
    Route::post('searchFichaRecreacion', [FichaRecreacionController::class, 'verFicharRecreacion']);
    Route::post('guardarFichaRecreacion', [FichaRecreacionController::class, 'guardarFichaRecreacion']);
    Route::post('actualizarFichaRecreacion', [FichaRecreacionController::class, 'actualizarFichaRecreacion']);

    

    Route::get('/estudiantes/{pApod}/{iIieeId}/{anio}', [EstudianteController::class, 'obtenerEstudiantesPorAnio']);

    Route::get('/ficha-pdf/{id}/{anio}', [FichaPdfController::class, 'mostrarFichaPdf'])->name('ficha.pdf');

    Route::post('listarEncuestas', [EncuestaBienestarController::class, 'listarEncuestas']);
    Route::get('crearEncuesta', [EncuestaBienestarController::class, 'crearEncuesta']);
    Route::post('guardarEncuesta', [EncuestaBienestarController::class, 'guardarEncuesta']);
    Route::post('actualizarEncuesta', [EncuestaBienestarController::class, 'actualizarEncuesta']);
    Route::post('actualizarEncuestaEstado', [EncuestaBienestarController::class, 'actualizarEncuestaEstado']);
    Route::post('verEncuesta', [EncuestaBienestarController::class, 'verEncuesta']);
    Route::post('borrarEncuesta', [EncuestaBienestarController::class, 'borrarEncuesta']);

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

});

