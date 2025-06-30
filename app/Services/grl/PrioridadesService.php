<?php

namespace App\Services\grl;

use App\Models\grl\Prioridad;

class PrioridadesService
{
    public static function obtenerPrioridades() {
        return Prioridad::selPrioridades();
    }
}
