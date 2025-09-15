<?php

namespace App\Http\Controllers\enc;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\enc\Respuesta;
use Exception;
use Illuminate\Http\Request;

class RespuestaController extends Controller
{
    public function listarRespuestas(Request $request)
    {
        try {
            $data = Respuesta::selRespuestas($request);
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
}
