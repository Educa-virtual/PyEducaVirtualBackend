<?php

namespace App\Services;

use Carbon\Carbon;

class FechaHoraService
{
    public static function convertirFechaUtcEnHoraLocal($fecha)
    {
        if ($fecha == null) {
            return null;
        } else {
            $fechaUTC = Carbon::parse($fecha);
            $fechaUTC->setTimezone(env('APP_TIMEZONE'));
            return $fechaUTC->format('H:i:s');
        }
    }

    public static function fechaInicioEsMayorFechaFin($fechaInicio, $fechaFin) {
        $fechaInicio = Carbon::parse($fechaInicio);
        $fechaFin = Carbon::parse($fechaFin);
        return $fechaInicio->gt($fechaFin);
    }
}
