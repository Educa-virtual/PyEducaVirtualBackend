<?php

use App\Http\Controllers\acad\BibliografiaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CredencialController;
use App\Http\Controllers\Ere\PreguntasController;
use App\Http\Controllers\Ere\TestWordController;
use App\Http\Controllers\MailController;

Route::get('/', function () {
    return view("welcome");
});

Route::get('/word', [TestWordController::class, 'word']);

Route::get('generarWordBancoPreguntasSeleccionadas', [PreguntasController::class, 'generarWordBancoPreguntasByIds']);
Route::get('generarWordEvaluacionByIds', [PreguntasController::class, 'generarWordEvaluacionByIds']);
