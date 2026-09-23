<?php

use App\Http\Controllers\bienestar\FichaPdfController;
use App\Http\Controllers\ere\PreguntasController;
use App\Http\Controllers\ere\TestWordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/word', [TestWordController::class, 'word']);

Route::get('generarWordBancoPreguntasSeleccionadas', [PreguntasController::class, 'generarWordBancoPreguntasByIds']);
Route::get('generarWordEvaluacionByIds', [PreguntasController::class, 'generarWordEvaluacionByIds']);

// ok
Route::get('mostrarPdf', [FichaPdfController::class, 'mostrarFichaPdf']);
