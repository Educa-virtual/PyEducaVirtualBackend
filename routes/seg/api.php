<?php

use App\Http\Controllers\api\grl\PersonaController;
use App\Http\Controllers\seg\AuditoriaAccesosFallidosController;
use App\Http\Controllers\seg\AuditoriaController;
use App\Http\Controllers\seg\AuditoriaMiddlewareController;
use App\Http\Controllers\seg\AuthController;
use App\Http\Controllers\seg\ModuloAdministrativoController;
use App\Http\Controllers\seg\PerfilController;
use App\Http\Controllers\seg\UsuarioController;
use App\Http\Middleware\RefreshToken;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'seg', 'middleware' => ['auth:api', RefreshToken::class]], function () {
    Route::group(['prefix' => 'auditoria'], function () {
        Route::get('accesos-autorizados', [AuditoriaController::class, 'obtenerAccesosAutorizados']);
        Route::get('accesos-fallidos', [AuditoriaController::class, 'obtenerAccesosFallidos']);
        Route::get('consultas-database', [AuditoriaController::class, 'obtenerConsultasDatabase']);
        Route::get('consultas-backend', [AuditoriaController::class, 'obtenerConsultasBackend']);
        /*Route::post('selAuditoriaAccesosFallidos', [AuditoriaAccesosFallidosController::class, 'selAuditoriaAccesosFallidos']);
        Route::post('selAuditoria', [AuditoriaController::class, 'selAuditoria']);
        Route::post('selAuditoriaMiddleware', [AuditoriaMiddlewareController::class, 'selAuditoriaMiddleware']);*/
    });

    Route::group(['prefix' => 'usuarios'], function () {
        Route::group(['prefix' => '{iCredId}'], function () {
            Route::get('perfiles', [UsuarioController::class, 'obtenerPerfilesUsuario']);
            Route::post('perfiles', [UsuarioController::class, 'agregarPerfilUsuario']);
            Route::delete('perfiles/{iCredEntPerfId}', [UsuarioController::class, 'eliminarPerfilUsuario']);
            Route::patch('estado', [UsuarioController::class, 'cambiarEstadoUsuario']);
            Route::patch('password', [UsuarioController::class, 'restablecerClaveUsuario']);
            Route::patch('fecha-vigencia', [UsuarioController::class, 'actualizarFechaVigenciaUsuario']);
        });
        Route::get('', [UsuarioController::class, 'obtenerListaUsuarios']);
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
