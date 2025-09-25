<?php

namespace App\Services\acad;

use App\Models\acad\IeCurso;

class IeCursosService
{
    public static function obtenerCursoPorIeCurso($ieCursoId)
    {
        return IeCurso::selCursoPorIeCurso($ieCursoId);
    }
}
