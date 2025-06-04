<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\com\ComunicadosController;
use App\Http\Controllers\com\GruposController;

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