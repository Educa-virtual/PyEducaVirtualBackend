<?php

namespace App\Services\acad;

use App\Models\acad\BuzonSugerencia;

class BuzonSugerenciasDirectorService
{
    public static function obtenerSugerencias($request) {
        return BuzonSugerencia::selBuzonSugerenciasDirector($request);
    }
}
