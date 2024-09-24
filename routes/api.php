<?php

use App\Http\Controllers\CredencialController;
use App\Http\Controllers\api\seg\sel\CredencialescCredUsuariocClaveController;
use App\Http\Controllers\api\seg\sel\ListarCursosController;
use App\Http\Controllers\MailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::get('/login', [CredencialescCredUsuariocClaveController::class,'login']);
Route::post('/verificar', [MailController::class,'index']);
Route::post('/verificar_codigo', [MailController::class,'comparar']);
Route::post('/listar_cursos', [ListarCursosController::class,'cursos']);

