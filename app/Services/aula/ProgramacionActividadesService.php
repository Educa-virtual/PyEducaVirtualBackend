<?php

namespace App\Services\aula;

use App\Models\aula\ProgramacionActividad;
use App\Services\acad\FechasImportantesService;
use Illuminate\Http\Request;

class ProgramacionActividadesService
{
    public static function obtenerCalendarioAcademicoEstudiante($matricula)
    {
        $fechas = FechasImportantesService::obtenerFechasImportantesCalendario($matricula->iSedeId, $matricula->iYAcadId);
        $calendario = ProgramacionActividad::selCalendarioAcademicoEstudiante($matricula->iMatrId);
        return array_merge($fechas, $calendario);
    }
}
