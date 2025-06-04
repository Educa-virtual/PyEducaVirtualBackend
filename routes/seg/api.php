<?php

use App\Http\Controllers\seg\PerfilController;
use App\Http\Controllers\seg\UsuarioController;
use App\Http\Middleware\RefreshToken;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'seg', 'middleware' => ['auth:api', RefreshToken::class]], function () {
    Route::group(['prefix' => 'usuarios'], function () {
        Route::get('{iCredId}/perfiles', [UsuarioController::class, 'obtenerPerfilesUsuario']);
        Route::delete('{iCredId}/perfiles/{iCredEntPerfId}', [UsuarioController::class, 'eliminarPerfilUsuario']);
        Route::patch('{iCredId}/estado', [UsuarioController::class, 'cambiarEstadoUsuario']);
        Route::patch('{iCredId}/password', [UsuarioController::class, 'restablecerClaveUsuario']);
        Route::get('perfiles', [UsuarioController::class, 'obtenerListaUsuariosPerfiles']);
    });
    Route::group(['prefix' => 'perfiles'], function () {
        Route::get('', [PerfilController::class, 'obtenerPerfiles']);
    });
});
