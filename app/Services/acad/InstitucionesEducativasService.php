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

    public static function obtenerIePorCodigoModular($codigoModular) {
        return InstitucionEducativa::selInstitucionEducativaPorCodigoModular($codigoModular);
    }

    public static function obtenerIePorSede($iSedeId) {
        return InstitucionEducativa::selInstitucionEducativaPorSede($iSedeId);
    }

    public static function obtenerIeNivel($iIieeId) {
        return InstitucionEducativa::selInstitucionEducativaNivel($iIieeId);
    }
}
