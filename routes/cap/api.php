<?php

use App\Http\Controllers\cap\CapacitacionesController;
use App\Http\Controllers\cap\CertificadoController;
use App\Http\Controllers\cap\InscripcionesController;
use App\Http\Controllers\cap\InstructoresController;
use App\Http\Controllers\cap\NivelPedagogicosController;
use App\Http\Controllers\cap\NotasController;
use App\Http\Controllers\cap\TipoCapacitacionesController;
use App\Http\Controllers\cap\TipoPublicosController;
use Illuminate\Support\Facades\Mail;
use App\Mail\CodigoMail;
use Illuminate\Support\Facades\Route;

Route::get('enviar-correo', function () {
  Mail::to('recipient@example.com')->send(new CodigoMail([
    'subject' => 'Test Email',
    'body' => 'This is a test email sent from the API.'
  ]));
  return response()->json(['message' => 'Email sent successfully']);
})->name('enviar-correo');

Route::group(['prefix' => 'cap'], function () {
  Route::group(['prefix' => 'tipo-capacitaciones'], function () {
    Route::get('/', [TipoCapacitacionesController::class, 'listarTipoCapacitaciones']);
  });
  Route::group(['prefix' => 'nivel-pedagogicos'], function () {
    Route::get('/', [NivelPedagogicosController::class, 'listarNivelPedagogicos']);
  });
  Route::group(['prefix' => 'tipo-publicos'], function () {
    Route::get('/', [TipoPublicosController::class, 'listarTipoPublicos']);
  });
  Route::group(['prefix' => 'capacitaciones'], function () {
    Route::post('/', [CapacitacionesController::class, 'guardarCapacitaciones']);
    Route::get('/', [CapacitacionesController::class, 'listarCapacitaciones']);
    Route::put('/{iCapacitacionId}', [CapacitacionesController::class, 'actualizarCapacitaciones']);
    Route::delete('/{iCapacitacionId}', [CapacitacionesController::class, 'eliminarCapacitaciones']);
    Route::put('/{iCapacitacionId}/estado', [CapacitacionesController::class, 'actualizarEstadoCapacitacion']);
    Route::get('/matriculados', [CapacitacionesController::class, 'listarCapacitacionesxMatriculados']); // Para listar las capacitaciones con sus inscripciones aprobadas
    Route::get('/publicadas', [CapacitacionesController::class, 'listarCapacitacionesPublicadas']);
    Route::get('/publicadas', [CapacitacionesController::class, 'listarCapacitacionesPublicadas']);
    Route::get('/{cPerfil}/{iCredId}', [CapacitacionesController::class, 'listarCapacitacionesxiCredId']);
  });
  Route::group(['prefix' => 'inscripciones'], function () {
    Route::get('capacitacion/{iCapacitacionId}/tipo/{iTipoIdentId}/documento/{cPersDocumento}', [InscripcionesController::class, 'buscarPersonaInscripcion']);
    Route::post('inscripciones', [InscripcionesController::class, 'listarInscripcionesxiCapacitacionId']);
    Route::post('inscripcion', [InscripcionesController::class, 'guardarInscripcion']);
    Route::put('/{iInscripId}/estado', [InscripcionesController::class, 'actualizarEstadoInscripcion']);
  });
  Route::prefix('instructores')->group(function () {
    Route::get('/{iTipoIdentId}/{cPersDocumento}', [InstructoresController::class, 'buscarInstructorxiTipoIdentIdxcPersDocumento']); // Para buscar
    Route::get('/', [InstructoresController::class, 'listarInstructores']); // Para listar
    Route::post('/', [InstructoresController::class, 'guardarInstructores']); // Para crear
    Route::put('/{iInstId}', [InstructoresController::class, 'actualizarInstructores']); // Para actualizar
    Route::delete('/{iInstId}', [InstructoresController::class, 'eliminarInstructores']); // Para eliminar
  });
  Route::prefix('notas')->group(function () {
    Route::get('/{iCapacitacionId}', [NotasController::class, 'obtenerNotaEstudiantes']);
    Route::post('/', [NotasController::class, 'calificarNotaEstudiantes']);
  });
  Route::prefix('certificado')->group(function () {
    Route::get('/{iCapacitacionId}/persona/{iPersId}/pdf', [CertificadoController::class, 'downloadPdf']);
  });
});
