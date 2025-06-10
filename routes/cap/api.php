<?php

use App\Http\Controllers\cap\CapacitacionesController;
use App\Http\Controllers\cap\InscripcionesController;
use App\Http\Controllers\cap\InstructoresController;
use App\Http\Controllers\cap\NivelPedagogicosController;
use App\Http\Controllers\cap\TipoCapacitacionesController;
use App\Http\Controllers\cap\TipoPublicosController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'cap'], function () {
  Route::group(['prefix' => 'tipo-capacitaciones'], function () {
    Route::post('listarTipoCapacitaciones', [TipoCapacitacionesController::class, 'listarTipoCapacitaciones']);
  });
  Route::group(['prefix' => 'nivel-pedagogicos'], function () {
    Route::post('listarNivelPedagogicos', [NivelPedagogicosController::class, 'listarNivelPedagogicos']);
  });
  Route::group(['prefix' => 'tipo-publicos'], function () {
    Route::post('listarTipoPublicos', [TipoPublicosController::class, 'listarTipoPublicos']);
  });
  Route::group(['prefix' => 'capacitaciones'], function () {
    Route::post('guardarCapacitaciones', [CapacitacionesController::class, 'guardarCapacitaciones']);
    Route::post('listarCapacitaciones', [CapacitacionesController::class, 'listarCapacitaciones']);
    Route::post('actualizarCapacitaciones', [CapacitacionesController::class, 'actualizarCapacitaciones']);
    Route::post('eliminarCapacitaciones', [CapacitacionesController::class, 'eliminarCapacitaciones']);
  });
  Route::group(['prefix' => 'inscripciones'], function () {
    Route::post('persona-inscripcion', [InscripcionesController::class, 'listarPersonaInscripcion']);
    Route::post('inscripciones', [InscripcionesController::class, 'listarInscripcionesxiCapacitacionId']);
    Route::post('inscripcion', [InscripcionesController::class, 'guardarInscripcion']);
  });
  Route::prefix('instructores')->group(function () {
    Route::get('/{iTipoIdentId}/{cPersDocumento}', [InstructoresController::class, 'buscarInstructorxiTipoIdentIdxcPersDocumento']); // Para buscar
    Route::get('/', [InstructoresController::class, 'listarInstructores']); // Para listar
    Route::post('/', [InstructoresController::class, 'guardarInstructores']); // Para crear
    Route::put('/{iInstId}', [InstructoresController::class, 'actualizarInstructores']); // Para actualizar
    Route::delete('/{iInstId}', [InstructoresController::class, 'eliminarInstructores']); // Para eliminar
  });
});
