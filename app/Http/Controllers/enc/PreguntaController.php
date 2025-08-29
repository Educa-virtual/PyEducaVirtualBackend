<?php

namespace App\Http\Controllers\enc;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\enc\Pregunta;
use Exception;
use Illuminate\Http\Request;

class PreguntaController extends Controller
{
    public function listarPreguntas(Request $request)
    {
        try {
            $data = Pregunta::selPreguntas($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verPregunta(Request $request)
    {
        try {
            $data = Pregunta::selPregunta($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarPregunta(Request $request)
    {
        try {
            $data = Pregunta::insPregunta($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarPregunta(Request $request)
    {
        try {
            $data = Pregunta::updPregunta($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarPregunta(Request $request)
    {
        try {
            $data = Pregunta::delPregunta($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
