<?php

namespace App\Http\Controllers\grl;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\grl\Prioridad;
use Illuminate\Http\Request;

class PrioridadController extends Controller
{
    public function obtenerPrioridades()
    {
        $prioridades = Prioridad::selPrioridades();
        return FormatearMensajeHelper::ok('Datos obtenidos', $prioridades);

    }
}
