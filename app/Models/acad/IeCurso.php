<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class IeCurso
{
    public static function selCursoPorIeCurso($ieCursoId)
    {
        return DB::selectOne("SELECT iCursoId
FROM acad.ies_cursos AS ic
INNER JOIN acad.cursos_niveles_grados AS cng ON cng.iCursosNivelGradId=ic.iCursosNivelGradId
WHERE ic.iIeCursoId=?", [$ieCursoId]);
    }
}
