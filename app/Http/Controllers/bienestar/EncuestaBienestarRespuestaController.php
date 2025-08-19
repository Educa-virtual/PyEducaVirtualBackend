<?php

namespace App\Http\Controllers\bienestar;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\bienestar\EncuestaBienestarRespuesta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EncuestaBienestarRespuestaController extends Controller
{
    private $perfiles_permitidos = [
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::DIRECTOR_IE,
        Perfil::SUBDIRECTOR_IE,
    ];

    public function listarRespuestas(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            $data = EncuestaBienestarRespuesta::selRespuestas($request);
            return FormatearMensajeHelper::ok('se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verRespuesta(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            $data = EncuestaBienestarRespuesta::selRespuesta($request);
            return FormatearMensajeHelper::ok('se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarRespuesta(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            $data = EncuestaBienestarRespuesta::insRespuesta($request);
            return FormatearMensajeHelper::ok('se guardó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarRespuesta(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            $data = EncuestaBienestarRespuesta::updRespuesta($request);
            return FormatearMensajeHelper::ok('se actualizó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarRespuesta(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            $data = EncuestaBienestarRespuesta::delRespuesta($request);
            return FormatearMensajeHelper::ok('se eliminó la respuesta', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function printRespuestas(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            $data = EncuestaBienestarRespuesta::selRespuestasDetalle($request);
            $encuesta = $data[0][0];
            $preguntas = $data[1];
            $respuestas = $data[2];

            foreach ( $respuestas as $respuesta) {
                $respuesta->respuestas = json_decode($respuesta->respuestas);
            }

            $nro_preguntas = count($preguntas);

            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            return view('bienestar.encuesta_respuestas_excel', compact('encuesta', 'preguntas', 'respuestas', 'nro_preguntas'));

        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

}
