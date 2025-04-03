<?php

use App\Http\Controllers\bienestar\FichaAlimentacionController;
use App\Http\Controllers\bienestar\FichaFamiliarController;
use App\Http\Controllers\bienestar\FichaGeneralController;
use App\Http\Controllers\bienestar\FichaBienestarController;
use App\Http\Controllers\bienestar\FichaRecreacionController;
use App\Http\Controllers\bienestar\FichaViviendaController;
use App\Http\Controllers\FichaEconomicoController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'bienestar'], function () {

    Route::get('searchFichasEstudiantes', [FichaGeneralController::class, 'indexEstudiantes']);
    Route::post('searchFichas', [FichaBienestarController::class, 'index']);
    Route::get('createFicha', [FichaBienestarController::class, 'create']);
    Route::delete('deleteFicha', [FichaBienestarController::class, 'delete']);
    Route::post('searchFicha', [FichaBienestarController::class, 'show']);

    Route::post('searchFichaGeneral', [FichaGeneralController::class, 'show']);
    Route::post('guardarFichaGeneral', [FichaGeneralController::class, 'save']);
    Route::post('actualizarFichaGeneral', [FichaGeneralController::class, 'update']);

    /* Rutas para gestion seccion economica */
    Route::post('searchFichaEconomico', [FichaEconomicoController::class, 'show']);
    Route::post('guardarFichaEconomico', [FichaEconomicoController::class, 'save']);
    Route::post('actualizarFichaEconomico', [FichaEconomicoController::class, 'update']);

    /* Rutas para gestionar familiares */
    Route::post('searchFichaFamiliares', [FichaFamiliarController::class, 'index']);
    Route::post('guardarFichaFamiliar', [FichaFamiliarController::class, 'save']);
    Route::post('searchFichaFamiliar', [FichaFamiliarController::class, 'show']);
    Route::post('actualizarFichaFamiliar', [FichaFamiliarController::class, 'update']);
    Route::post('borrarFichaFamiliar', [FichaFamiliarController::class, 'delete']);

    Route::post('searchFichaVivienda', [FichaViviendaController::class, 'show']);
    Route::post('guardarFichaVivienda', [FichaViviendaController::class, 'save']);
    Route::post('actualizarFichaVivienda', [FichaViviendaController::class, 'update']);

    Route::post('searchFichaRecreacion', [FichaRecreacionController::class, 'show']);
    Route::post('guardarFichaRecreacion', [FichaRecreacionController::class, 'save']);
    Route::post('actualizarFichaRecreacion', [FichaRecreacionController::class, 'update']);

    Route::post('searchFichaAlimentacion', [FichaAlimentacionController::class, 'show']);
    Route::post('guardarFichaAlimentacion', [FichaAlimentacionController::class, 'save']);
    Route::post('actualizarFichaAlimentacion', [FichaAlimentacionController::class, 'update']);

});
