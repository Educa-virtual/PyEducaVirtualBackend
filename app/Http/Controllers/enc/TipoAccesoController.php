<?php

namespace App\Http\Controllers\enc;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Services\enc\TiposAccesoService;
use Exception;

class TipoAccesoController extends Controller
{
    public function obtenerTiposAcceso() {
        try {
            $data = TiposAccesoService::obtenerTiposAcceso();
            return FormatearMensajeHelper::ok('Datos obtenidos correctamente', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
