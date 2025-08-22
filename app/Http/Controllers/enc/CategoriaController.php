<?php

namespace App\Http\Controllers\enc;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\enc\ActualizarCategoriaRequest;
use App\Http\Requests\enc\InsertarCategoriaRequest;
use App\Http\Requests\enc\RegistrarCategoriaRequest;
use App\Models\enc\Categoria;
use Exception;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function obtenerCategorias(Request $request)
    {
        try {
            $data = Categoria::selCategorias($request);
            return FormatearMensajeHelper::ok('Datos obtenidos correctamente', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function obtenerDetallesCategoria(Request $request)
    {
        try {
            $data = Categoria::selCategoria($request);
            return FormatearMensajeHelper::ok('Datos obtenidos correctamente', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function registrarCategoria(RegistrarCategoriaRequest $request)
    {
        try {
            $id=Categoria::insCategoria($request);
            return FormatearMensajeHelper::ok('Categoría registrada correctamente', ['id' => $id]);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarCategoria(ActualizarCategoriaRequest $request)
    {
        try {
            Categoria::updCategoria($request);
            return FormatearMensajeHelper::ok('Categoría actualizada correctamente');
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function eliminarCategoria(Request $request)
    {
        try {
            Categoria::delCategoria($request);
            return FormatearMensajeHelper::ok('Categoría eliminada correctamente');
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
