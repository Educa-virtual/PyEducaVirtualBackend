<?php

namespace App\Services\acad;

use App\Models\acad\CalendarioAcademico;

class CalendariosAcademicosService
{
    public static function obtenerCalendarioFechasInicioFinSede($iYAcadId, $iSedeId)
    {
        return CalendarioAcademico::selCalendarioFechasInicioFinSede($iYAcadId, $iSedeId);
    }
}
