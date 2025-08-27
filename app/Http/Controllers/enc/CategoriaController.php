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
    public function listarCategorias(Request $request)
    {
        try {
            $data = Categoria::selCategorias($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verCategoria(Request $request)
    {
        try {
            $data = Categoria::selCategoria($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarCategoria(RegistrarCategoriaRequest $request)
    {
        try {
            $data = Categoria::insCategoria($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarCategoria(ActualizarCategoriaRequest $request)
    {
        try {
            $data = Categoria::updCategoria($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarCategoria(Request $request)
    {
        try {
            $data = Categoria::delCategoria($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
