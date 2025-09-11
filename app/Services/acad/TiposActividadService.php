<?php

namespace App\Services\acad;

use App\Models\acad\TipoActividad;

class TiposActividadService
{
    public static function obtenerTiposActividad()
    {
        return TipoActividad::selTipoActividad();
    }
}
