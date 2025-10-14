<?php

namespace App\Services\asi;

use App\Models\asi\AsistenciaControl;

class AsistenciaControlService
{
    public static function obtenerAsistenciaEstudiantePorFecha($matricula, $fecha)
    {
        return AsistenciaControl::selAsistenciaEstudiantePorFecha($matricula, $fecha);
    }
}
