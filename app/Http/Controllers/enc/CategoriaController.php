<?php

namespace App\Http\Controllers\enc;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\enc\ActualizarCategoriaRequest;
use App\Http\Requests\enc\InsertarCategoriaRequest;
use App\Http\Requests\enc\RegistrarCategoriaRequest;
use App\Services\enc\CategoriasService;
use Exception;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function obtenerCategorias()
    {
        try {
            $data = CategoriasService::obtenerCategorias();
            return FormatearMensajeHelper::ok('Datos obtenidos correctamente', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function registrarCategoria(RegistrarCategoriaRequest $request)
    {
        try {
            $id=CategoriasService::registrarCategoria($request);
            return FormatearMensajeHelper::ok('Categoría registrada correctamente', ['id' => $id]);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function actualizarCategoria(ActualizarCategoriaRequest $request)
    {
        try {
            CategoriasService::actualizarCategoria($request);
            return FormatearMensajeHelper::ok('Categoría actualizada correctamente');
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function eliminarCategoria(Request $request)
    {
        try {
            CategoriasService::eliminarCategoria($request);
            return FormatearMensajeHelper::ok('Categoría eliminada correctamente');
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
