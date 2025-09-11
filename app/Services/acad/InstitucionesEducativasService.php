<?php

namespace App\Services\acad;

use App\Models\acad\InstitucionEducativa;
use App\Models\acad\YearAcademico;

class InstitucionesEducativasService
{
    public static function obtenerInstitucionEducativa($iIieeId)
    {
        return InstitucionEducativa::selInstitucionEducativa($iIieeId);
    }
}
