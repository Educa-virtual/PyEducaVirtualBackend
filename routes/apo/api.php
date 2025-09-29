<?php

use App\Http\Controllers\acad\ApoderadoController;
use App\Http\Middleware\RefreshToken;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'apo', 'middleware' => ['auth:api', RefreshToken::class]], function () {
    Route::group(['prefix' => 'estudiantes'], function () {
        Route::get('', [ApoderadoController::class, 'obtenerEstudiantes']);
    });
});
