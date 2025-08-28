<?php

namespace App\Http\Controllers\enc;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\enc\Encuesta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EncuestaController extends Controller
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

    public function listarEncuestas(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->encuestadores, $this->encuestados)]);
            $data = Encuesta::selEncuestas($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function crearEncuesta(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Encuesta::selEncuestaParametros($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verEncuesta(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->encuestadores, $this->encuestados)]);
            $data = Encuesta::selEncuesta($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarEncuesta(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Encuesta::insEncuesta($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarEncuesta(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Encuesta::delEncuesta($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarEncuesta(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Encuesta::updEncuesta($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function obtenerPoblacionObjetivo(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->encuestadores, $this->encuestados)]);
            $data = Encuesta::selPoblacionObjetivo($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarEncuestaEstado(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Encuesta::updEncuestaEstado($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
