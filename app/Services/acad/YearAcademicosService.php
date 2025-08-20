<?php

namespace App\Services\acad;

use App\Models\acad\YearAcademico;

class YearAcademicosService
{
    public static function obtenerYearAcademico($iYAcadId)
    {
        return YearAcademico::selYearAcademico($iYAcadId);
    }
}
