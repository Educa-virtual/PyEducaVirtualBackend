<?php

use App\Http\Controllers\bienestar\FichaGeneralController;
use App\Http\Controllers\FichaBienestarController;
use App\Http\Controllers\FichaFamiliarController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'bienestar'], function () {

    Route::get('createFichaGeneral', [FichaGeneralController::class, 'createGeneral']);
    Route::post('searchFichaGeneral', [FichaGeneralController::class, 'showGeneral']);
    Route::post('guardarFichaGeneral', [FichaGeneralController::class, 'saveGeneral']);
    Route::post('actualizarFichaGeneral', [FichaGeneralController::class, 'updateGeneral']);

    /* Rutas para gestionar familiares */
    Route::post('searchFichaFamiliares', [FichaFamiliarController::class, 'index']);
    Route::post('guardarFichaFamiliar', [FichaFamiliarController::class, 'save']);

});
