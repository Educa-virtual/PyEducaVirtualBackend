<?php

namespace App\Http\Controllers\enc;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\enc\Encuesta;
use Exception;
use Illuminate\Http\Request;

class EncuestaController extends Controller
{
    public function listarEncuestas(Request $request) {
        try {
            $data = Encuesta::selEncuestas($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function crearEncuesta(Request $request) {
        try {
            $data = Encuesta::selEncuestaParametros($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verEncuesta(Request $request) {
        try {
            $data = Encuesta::obtenerEstudiantesParaFiltroEncuesta($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarEncuesta(Request $request) {
        try {
            $data = Encuesta::insEncuesta($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarEncuesta(Request $request) {
        try {
            $data = Encuesta::delEncuesta($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarEncuesta(Request $request) {
        try {
            $data = Encuesta::updEncuesta($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function obtenerPoblacionObjetivo(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            $data = Encuesta::selPoblacionObjetivo($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
