<?php

namespace App\Http\Controllers\bienestar;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\bienestar\EncuestaBienestarPregunta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EncuestaBienestarPreguntaController extends Controller
{
    private $perfiles_permitidos = [
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::DIRECTOR_IE,
        Perfil::SUBDIRECTOR_IE,
    ];

    public function listarPreguntas(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            $data = EncuestaBienestarPregunta::selPreguntas($request);
            return FormatearMensajeHelper::ok('se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verPregunta(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            $data = EncuestaBienestarPregunta::selPregunta($request);
            return FormatearMensajeHelper::ok('se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarPregunta(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            $data = EncuestaBienestarPregunta::insPregunta($request);
            return FormatearMensajeHelper::ok('se guardó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarPregunta(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            $data = EncuestaBienestarPregunta::updPregunta($request);
            return FormatearMensajeHelper::ok('se actualizó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarPregunta(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            $data = EncuestaBienestarPregunta::delPregunta($request);
            return FormatearMensajeHelper::ok('se eliminó la pregunta', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

}
