<?php

namespace App\Http\Controllers\enc;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\enc\Respuesta;
use Exception;
use Illuminate\Http\Request;

class RespuestaController extends Controller
{
    /**
     * Muestra todas las respuestas de una encuesta
     */
    public function listarRespuestas(Request $request)
    {
        try {
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
            $data = Respuesta::selRespuesta($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarRespuestas(Request $request)
    {
        try {
            $data = Respuesta::insRespuestas($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarRespuestas(Request $request)
    {
        try {
            $data = Respuesta::updRespuestas($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function imprimirRespuestas(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            $data = Respuesta::selRespuestasDetalle($request);
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
            return view('encuestas.respuestas_excel', compact('encuesta', 'preguntas', 'respuestas', 'nro_preguntas'));

        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
