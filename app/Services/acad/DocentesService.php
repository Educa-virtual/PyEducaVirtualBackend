<?php

namespace App\Services\acad;

use App\Models\acad\Docente;

class DocentesService
{
    public static function obtenerDocentePorId($iDocenteId)
    {
        return Docente::selDocentePorId($iDocenteId);
    }
}
