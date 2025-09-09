<?php

namespace App\Services\acad;

use App\Models\acad\DocenteCurso;

class DocentesCursosService
{
    public static function obtenerTutorSalonIe($iYAcadId, $iSedeId, $iNivelGradoId, $iSeccionId)
    {
        return DocenteCurso::selTutorSalonIe($iYAcadId, $iSedeId, $iNivelGradoId, $iSeccionId);
    }
}
