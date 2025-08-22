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
            return FormatearMensajeHelper::ok('Datos obtenidos correctamente', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function crearEncuesta(Request $request) {
        try {
            $data = Encuesta::selEncuestaParametros($request);
            return FormatearMensajeHelper::ok('Configuración registrada correctamente');
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verEncuesta(Request $request) {
        try {
            $data = Encuesta::obtenerEstudiantesParaFiltroEncuesta($request);
            return FormatearMensajeHelper::ok('Datos obtenidos correctamente', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarEncuesta(Request $request) {
        try {
            $data = Encuesta::insEncuesta($request);
            return FormatearMensajeHelper::ok('Configuración registrada correctamente');
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarEncuesta(Request $request) {
        try {
            Encuesta::delEncuesta($request);
            return FormatearMensajeHelper::ok('Encuesta eliminada correctamente');
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarEncuesta(Request $request) {
        try {
            Encuesta::updEncuesta($request);
            return FormatearMensajeHelper::ok('Se han actualizado los accesos de la encuesta');
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
