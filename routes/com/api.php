<?php

use App\Http\Controllers\com\ComunicadoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\com\ComunicadosController;
use App\Http\Controllers\com\GruposController;
use App\Http\Middleware\RefreshToken;

Route::group(['prefix' => 'com'], function () {
    Route::group(['prefix' => 'comunicado'], function () {
        Route::post('registrar_comunicado', [ComunicadosController::class, 'registrar']);
        Route::post('obtener_comunicado', [ComunicadosController::class, 'obtener']);
        Route::post('obtener_datos', [ComunicadosController::class, 'obtenerDatos']);
        Route::post('obtener_comunicados_persona', [ComunicadosController::class, 'obtenerComunicadosPersona']);
        Route::post('eliminar', [ComunicadosController::class, 'eliminar']);
        Route::post('actualizar_comunicado', [ComunicadosController::class, 'actualizar']);
        Route::post('obtener_comunicados_destino', [ComunicadosController::class, 'obtenerComunicadosDestino']);
        Route::post('comunicado_personalizado', [ComunicadosController::class, 'obtenerComunicadoPersonalizado']);
        Route::post('obtener_institucionesEspecialista', [ComunicadosController::class, 'obtenerInstitucionesEspecialista']);
        Route::post('obtener_docentes_por_institucion', [ComunicadosController::class, 'obtenerDocentesPorInstitucion']);

    });
    Route::group(['prefix' => 'miembros'], function () {
        Route::post('obtener_grupos', [GruposController::class, 'obtenerGrupo']);
        Route::post('obtener_miembros', [GruposController::class, 'obtenerDatosMiembros']);
        Route::post('guardar_miembros', [GruposController::class, 'guardarMiembros']);
        Route::post('actualizar_grupo', [GruposController::class, 'actualizarGrupo']);
    });
});

Route::group(['prefix' => 'com', 'middleware' => ['auth:api']], function () {
    Route::post('listarComunicados', [ComunicadoController::class, 'listarComunicados']);
    Route::post('crearComunicado', [ComunicadoController::class, 'crearComunicado']);
    Route::post('verComunicado', [ComunicadoController::class, 'verComunicado']);
    Route::post('guardarComunicado', [ComunicadoController::class, 'guardarComunicado']);
    Route::post('actualizarComunicado', [ComunicadoController::class, 'actualizarComunicado']);
    Route::post('borrarComunicado', [ComunicadoController::class, 'borrarComunicado']);
    Route::post('obtenerGrupoCantidad', [ComunicadoController::class, 'obtenerGrupoCantidad']);
    Route::post('buscarPersona', [ComunicadoController::class, 'buscarPersona']);
    Route::post('subirDocumento', [ComunicadoController::class, 'subirDocumento']);
    Route::post('descargarDocumento', [ComunicadoController::class, 'descargarDocumento']);
    Route::post('recepcionarComunicado', [ComunicadoController::class, 'recepcionarComunicado']);
});