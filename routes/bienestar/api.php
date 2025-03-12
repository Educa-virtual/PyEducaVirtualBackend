<?php

use App\Http\Controllers\FichaBienestarController;
use App\Http\Controllers\FichaFamiliarController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'bienestar'], function () {

    Route::post('guardarFichaGeneral', [FichaBienestarController::class, 'saveGeneral']);
    Route::post('actualizarFichaGeneral', [FichaBienestarController::class, 'updateGeneral']);


    /* Rutas para gestionar familiares */
    Route::post('searchFichaFamiliares', [FichaFamiliarController::class, 'index']);
    Route::post('guardarFichaFamiliar', [FichaFamiliarController::class, 'save']);

});
