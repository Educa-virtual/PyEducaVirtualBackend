<?php

use App\Http\Controllers\api\grl\PersonaController;
use App\Http\Controllers\seg\ModuloAdministrativoController;
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
        Route::patch('{iCredId}/fecha-vigencia', [UsuarioController::class, 'actualizarFechaVigenciaUsuario']);
        Route::get('perfiles', [UsuarioController::class, 'obtenerListaUsuariosPerfiles']);
        Route::post('', [UsuarioController::class, 'registrarUsuario']);
    });
    Route::group(['prefix' => 'personas'], function () {
        Route::get('', [PersonaController::class, 'buscarPersona']);
    });
    Route::group(['prefix' => 'perfiles'], function () {
        Route::get('', [PerfilController::class, 'obtenerPerfiles']);
    });
    Route::group(['prefix' => 'modulos-administrativos'], function () {
        Route::get('', [ModuloAdministrativoController::class, 'obtenerModulos']);
    });
});
