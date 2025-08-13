<?php

namespace App\Services\acad;

use App\Models\acad\Matricula;

class MatriculasService
{
    public static function obtenerDetallesMatriculaEstudiante($iCredEntPerfId)
    {
        return Matricula::selDetalleMatriculaEstudiante($iCredEntPerfId);
    }
}
