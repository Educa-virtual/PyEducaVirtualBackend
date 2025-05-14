<?php

use App\Http\Controllers\seg\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'seg'], function () {
    Route::group(['prefix' => 'usuarios'], function () {
        Route::get('perfiles', [UsuarioController::class, 'obtenerListaUsuariosPerfiles']);
        Route::patch('{iCredId}/estado', [UsuarioController::class, 'cambiarEstadoUsuario']);
    });

});
