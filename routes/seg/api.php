<?php

use App\Http\Controllers\seg\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'seg'], function () {
    Route::group(['prefix' => 'usuarios'], function () {
        Route::get('{iCredId}/perfiles', [UsuarioController::class, 'obtenerPerfilesUsuario']);
        Route::patch('{iCredId}/estado', [UsuarioController::class, 'cambiarEstadoUsuario']);
        Route::get('perfiles', [UsuarioController::class, 'obtenerListaUsuariosPerfiles']);

    });

});
