<?php

namespace App\Services\enc;

use App\Models\enc\TiempoDuracion;

class TiemposDuracionService
{
    public static function obtenerTiemposDuracion()
    {
        return TiempoDuracion::selTiemposDuracion();
    }
}
