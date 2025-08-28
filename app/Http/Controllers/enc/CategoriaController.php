<?php

namespace App\Http\Controllers\enc;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\enc\ActualizarCategoriaRequest;
use App\Http\Requests\enc\RegistrarCategoriaRequest;
use App\Models\enc\Categoria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoriaController extends Controller
{
    private $encuestadores = [
        Perfil::ADMINISTRADOR_DREMO,
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::DIRECTOR_IE,
        Perfil::SUBDIRECTOR_IE,
    ];

    private $encuestados = [
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::DIRECTOR_IE,
        Perfil::SUBDIRECTOR_IE,
        Perfil::DOCENTE,
        Perfil::ESTUDIANTE,
        Perfil::APODERADO,
    ];

    public function listarCategorias(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->encuestadores, $this->encuestados)]);
            $data = Categoria::selCategorias($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verCategoria(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->encuestadores, $this->encuestados)]);
            $data = Categoria::selCategoria($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarCategoria(RegistrarCategoriaRequest $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Categoria::insCategoria($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarCategoria(ActualizarCategoriaRequest $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Categoria::updCategoria($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarCategoria(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Categoria::delCategoria($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
