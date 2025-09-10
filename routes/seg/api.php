<?php

use App\Http\Controllers\api\grl\PersonaController;
use App\Http\Controllers\seg\AuditoriaAccesosFallidosController;
use App\Http\Controllers\seg\AuditoriaController;
use App\Http\Controllers\seg\AuditoriaMiddlewareController;
use App\Http\Controllers\seg\AuthController;
use App\Http\Controllers\seg\CredencialModuloController;
use App\Http\Controllers\seg\DatabaseController;
use App\Http\Controllers\seg\ModuloAdministrativoController;
use App\Http\Controllers\seg\PasswordRecoveryController;
use App\Http\Controllers\seg\PerfilController;
use App\Http\Controllers\seg\UsuarioController;
use App\Http\Middleware\RefreshToken;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'seg', 'middleware' => ['auth:api', RefreshToken::class]], function () {

    Route::group(['prefix' => 'acceso_modulos'], function () {
        Route::post('list', [CredencialModuloController::class, 'list']);
    });
    Route::group(['prefix' => 'database'], function () {
        Route::group(['prefix' => 'backups'], function () {
            Route::post('', [DatabaseController::class, 'realizarBackupBd']);
            Route::get('', [DatabaseController::class, 'obtenerHistorialBackups']);
            Route::get('configuracion', [DatabaseController::class, 'obtenerConfiguracionBackup']);
        });
    });

    Route::group(['prefix' => 'auditoria'], function () {
        Route::get('accesos-autorizados', [AuditoriaController::class, 'obtenerAccesosAutorizados']);
        Route::get('accesos-fallidos', [AuditoriaController::class, 'obtenerAccesosFallidos']);
        Route::get('consultas-database', [AuditoriaController::class, 'obtenerConsultasDatabase']);
        Route::get('consultas-backend', [AuditoriaController::class, 'obtenerConsultasBackend']);
    });

    Route::group(['prefix' => 'usuarios'], function () {
        Route::group(['prefix' => 'password-recovery'], function () {
            Route::post('codigo-recuperacion', [PasswordRecoveryController::class, 'enviarCodigoRecuperacion'])->withoutMiddleware('auth:api');
            Route::post('codigo-recuperacion/validar', [PasswordRecoveryController::class, 'validarCodigoRecuperacion'])->withoutMiddleware('auth:api');
            Route::post('reset-password', [PasswordRecoveryController::class, 'resetPassword'])->withoutMiddleware('auth:api');
        });

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
