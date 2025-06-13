<?php

namespace App\Http\Controllers\grl;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Services\grl\PrioridadesService;
use Illuminate\Http\Request;

class PrioridadController extends Controller
{
    public function obtenerPrioridades()
    {
        $prioridades = PrioridadesService::obtenerPrioridades();
        return FormatearMensajeHelper::ok('Datos obtenidos', $prioridades);

    }
}
