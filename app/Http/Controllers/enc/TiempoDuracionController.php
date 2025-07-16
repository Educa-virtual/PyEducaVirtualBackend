<?php

namespace App\Http\Controllers\enc;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use App\Services\enc\TiemposDuracionService;
use Illuminate\Http\Response;

class TiempoDuracionController extends Controller
{
    public function obtenerTiemposDuracion()
    {
        try {
            $data=TiemposDuracionService::obtenerTiemposDuracion();
            return FormatearMensajeHelper::ok('Datos obtenidos correctamente', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
