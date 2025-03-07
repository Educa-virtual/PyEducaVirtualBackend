<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\com\ComunicadosController;

Route::group(['prefix' => 'com'], function () {
    Route::group(['prefix' => 'comunicado'], function () {
        Route::post('registrar_comunicado', [ComunicadosController::class, 'registrar']);
        Route::post('obtener_comunicado', [ComunicadosController::class, 'obtener']);
        Route::post('obtener_datos', [ComunicadosController::class, 'obtenerDatos']);
<<<<<<< HEAD
        Route::post('obtener_comunicados_persona', [ComunicadosController::class, 'obtenerComunicadosPersona']);
        Route::post('eliminar', [ComunicadosController::class, 'eliminar']);
        Route::post('actualizar_comunicado', [ComunicadosController::class, 'actualizar']);
=======
    });
    Route::group(['prefix' => 'miembros'], function () {
        Route::post('obtener_miembros', [ComunicadosController::class, 'obtenerDatosMiembros']);
>>>>>>> b4a083344c8c51e97aee83effff274feb5ec6db2
    });
});