<?php

namespace App\Http\Controllers\enc;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\enc\Respuesta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RespuestaController extends Controller
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

    /**
     * Muestra todas las respuestas de una encuesta
     */
    public function listarRespuestas(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->encuestadores, $this->encuestados)]);
            $data = Respuesta::selRespuestas($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    /**
     * Muestra las respuestas de una persona
     */
    public function verRespuestas(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->encuestadores, $this->encuestados)]);
            $data = Respuesta::selRespuesta($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarRespuestas(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestados]);
            $data = Respuesta::insRespuestas($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarRespuestas(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestados]);
            $data = Respuesta::updRespuestas($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function imprimirRespuestas(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Respuesta::selRespuestasDetalle($request);
            $encuesta = $data[0][0];
            $preguntas = $data[1];
            $respuestas = $data[2]; 
            $filtros = $data[3][0];

            foreach ( $respuestas as $respuesta) {
                $respuesta->respuestas = json_decode($respuesta->respuestas);
            }

            $nro_preguntas = count($preguntas);

            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            return view('enc.respuestas_excel', compact('encuesta', 'preguntas', 'respuestas', 'nro_preguntas', 'filtros'));

        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
