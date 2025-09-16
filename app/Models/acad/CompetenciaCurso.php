<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class CompetenciaCurso
{
    public static function selCursosPorIe($iSedeId, $iNivelGradoId, $iYAcadId)
    {
        return DB::select("EXEC acad.SP_SEL_cursosPorIeNivelGradoAnioAcad @iSedeId=?, @iNivelGradoId=?, @iYAcadId=?", [$iSedeId, $iNivelGradoId, $iYAcadId]);
    }

    public static function selCompetenciasPorCurso($iNivelTipoId, $iCursoId)
    {
        return DB::select("SELECT compcur.iCompetenciaId, cCompetenciaNombre
FROM acad.competencias_cursos AS compcur
INNER JOIN acad.curriculo_competencias AS curcomp ON curcomp.iCompetenciaId=compcur.iCompetenciaId
WHERE compcur.iEstado=1 AND compcur.iNivelTipoId=? AND compcur.iCursoId=?", [$iNivelTipoId, $iCursoId]);
        //return DB::select("EXEC [acad].[Sp_SEL_competencias_cursos] @_iCursoId=?, @_iNivelTipoId=?", [$iCursoId, $iNivelTipoId]);
    }
}
