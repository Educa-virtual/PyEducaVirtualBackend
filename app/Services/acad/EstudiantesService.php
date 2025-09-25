<?php

namespace App\Services\acad;

use App\Models\acad\Estudiante;

class EstudiantesService
{
    public static function obtenerIdEstudiantePorIdPersona($iEstudianteId)
    {
        return Estudiante::selIdEstudiantePorIdPersona($iEstudianteId);
    }

    public static function obtenerIdCredIdPersEstudiantePorIeDocumento($cPersDocumento, $iSedeId) {
        return Estudiante::selIdCredIdPersEstudiantePorIeDocumento($cPersDocumento, $iSedeId);
    }
}
