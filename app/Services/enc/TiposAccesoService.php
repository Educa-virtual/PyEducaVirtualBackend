<?php

namespace App\Services\enc;

use App\Models\enc\TipoAcceso;
use Illuminate\Http\Request;

class TiposAccesoService
{
    public static function obtenerTiposAcceso()
    {
        return TipoAcceso::selTiposAcceso();
    }
}
