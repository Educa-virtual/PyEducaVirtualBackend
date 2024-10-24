<?php

use App\Http\Controllers\grl\GeneralController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'general'], function () {
    Route::post('subir-archivo', [GeneralController::class, 'subirArchivo']);
});
