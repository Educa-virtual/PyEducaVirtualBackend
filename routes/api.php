<?php

use App\Http\Controllers\api\acad\ActividadesAprendizajeController;
use App\Http\Controllers\api\acad\BibliografiaController;
use App\Http\Controllers\CredencialController;
use App\Http\Controllers\api\seg\sel\CredencialescCredUsuariocClaveController;
use App\Http\Controllers\api\seg\sel\ListarCursosController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'auth'
// ], function ($router) {
//     Route::post('/register', [AuthController::class, 'register'])->name('register');
//     Route::post('/login', [AuthController::class, 'login'])->name('login');
//     Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
//     Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
//     Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
// });

Route::post('/login', [CredencialescCredUsuariocClaveController::class,'login']);
Route::post('/verificar', [MailController::class,'index']);
Route::post('/verificar_codigo', [MailController::class,'comparar']);
Route::post('/listar_cursos', [ListarCursosController::class,'cursos']);
Route::post('/save_actividades',[ActividadesAprendizajeController::class,'save']);
Route::post('/listar_actividades',[ActividadesAprendizajeController::class,'list']);
Route::post('/del_actividades',[ActividadesAprendizajeController::class,'save']);
Route::post('/upd_actividades',[ActividadesAprendizajeController::class,'list']);
Route::post('/save_biblio',[BibliografiaController::class,'save']);
Route::post('/listar_biblio',[BibliografiaController::class,'list']);
Route::post('/del_biblio',[BibliografiaController::class,'save']);
Route::post('/upd_biblio',[BibliografiaController::class,'list']);