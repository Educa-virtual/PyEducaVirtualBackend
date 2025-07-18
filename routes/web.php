<?php

use App\Http\Controllers\acad\BibliografiaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CredencialController;
use App\Http\Controllers\ere\PreguntasController;
use App\Http\Controllers\ere\TestWordController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\bienestar\FichaPdfController;

Route::get('/', function () {
    return view("welcome");
});

Route::get('/word', [TestWordController::class, 'word']);

Route::get('generarWordBancoPreguntasSeleccionadas', [PreguntasController::class, 'generarWordBancoPreguntasByIds']);
Route::get('generarWordEvaluacionByIds', [PreguntasController::class, 'generarWordEvaluacionByIds']);

//ok
Route::get('mostrarPdf', [FichaPdfController::class, 'mostrarFichaPdf']);

