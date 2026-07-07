<?php

namespace App\Services\enc;

use App\Models\enc\TipoAcceso;

class TiposAccesoService
{
    public static function obtenerTiposAcceso()
    {
        return TipoAcceso::selTiposAcceso();
    }
}
