<?php

namespace App\Http\Controllers\enc;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\enc\Pregunta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PreguntaController extends Controller
{
    private $encuestadores = [
        Perfil::ADMINISTRADOR_DREMO,
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::DIRECTOR_IE,
    ];

    private $encuestados = [
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::DIRECTOR_IE,
        Perfil::DOCENTE,
        Perfil::ESTUDIANTE,
        Perfil::APODERADO,
    ];

    public function listarPreguntas(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->encuestadores, $this->encuestados)]);
            $data = Pregunta::selPreguntas($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verPregunta(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Pregunta::selPregunta($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarPregunta(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Pregunta::insPregunta($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarPregunta(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Pregunta::updPregunta($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarPregunta(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Pregunta::delPregunta($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
