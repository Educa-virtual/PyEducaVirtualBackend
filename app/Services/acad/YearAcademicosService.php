<?php

namespace App\Services\acad;

use App\Models\acad\YearAcademico;

class YearAcademicosService
{
    public static function obtenerYearAcademicoPorId($iYAcadId)
    {
        return YearAcademico::selYearAcademicoPorId($iYAcadId) ;
    }

    public static function obtenerYearAcademicoPorAnio($anio)
    {
        return YearAcademico::selYearAcademicoPorAnio($anio);
    }
}
