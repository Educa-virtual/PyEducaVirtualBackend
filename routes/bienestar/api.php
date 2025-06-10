<?php

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

    Route::post('listarEstudiantesApoderado', [FichaBienestarController::class, 'listarEstudiantesApoderado']);
    Route::post('listarFichas', [FichaBienestarController::class, 'listarFichas']);
    Route::post('crearFicha', [FichaBienestarController::class, 'crearFicha']);
    Route::get('obtenerParametrosFicha', [FichaBienestarController::class, 'obtenerParametrosFicha']);
    Route::delete('borrarFicha', [FichaBienestarController::class, 'borrarFicha']);
    Route::post('verFicha', [FichaBienestarController::class, 'verFicha']);

    Route::post('descargarFicha', [FichaPdfController::class, 'descargarFicha']);

    Route::post('searchFichaGeneral', [FichaGeneralController::class, 'verFichaGeneral']);
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

});

