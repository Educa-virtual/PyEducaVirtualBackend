<?php

namespace App\Services\aula;

use App\Models\aula\ProgramacionActividad;
use Illuminate\Http\Request;

class ProgramacionActividadesService
{
    public static function obtenerCalendarioAcademicoEstudiante($iMatrId) {
        $params=[];
        return ProgramacionActividad::selCalendarioAcademicoEstudiante($iMatrId);
    }
}
