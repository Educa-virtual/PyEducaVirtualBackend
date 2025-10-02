<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class CompetenciaCurso
{
    public static function selCursosPorIe($iSedeId, $iYAcadId, $iNivelGradoId)
    {
        return DB::select("SELECT iesc.iIeCursoId,acunig.iCursosNivelGradId,acur.iCursoId, c.iNivelTipoId, acur.cCursoNombre,
(SELECT COUNT(compcur.iCompetenciaId)

FROM acad.competencias_cursos AS compcur
INNER JOIN acad.curriculo_competencias AS curcomp ON curcomp.iCompetenciaId=compcur.iCompetenciaId
WHERE compcur.iEstado=1 AND compcur.iNivelTipoId=nivcic.iNivelTipoId AND compcur.iCursoId=acur.iCursoId) AS iCantidadFilas

FROM acad.configuraciones c
INNER JOIN acad.ies_cursos AS iesc ON  iesc.iConfigId = c.iConfigId
INNER JOIN acad.cursos_niveles_grados AS acunig ON acunig.iCursosNivelGradId=iesc.iCursosNivelGradId
INNER JOIN acad.nivel_grados AS nivgr ON nivgr.iNivelGradoId=acunig.iNivelGradoId
INNER JOIN acad.nivel_ciclos AS nivcic ON nivcic.iNivelCicloId=nivgr.iNivelCicloId
INNER JOIN acad.cursos AS acur ON acur.iCursoId=acunig.iCursoId
WHERE iesc.iEstado=1
AND c.iSedeId=? AND c.iYAcadId=?
AND acur.iTipoCursoId=1 AND nivgr.iNivelGradoId=?
ORDER BY cCursoNombre", [$iSedeId, $iYAcadId, $iNivelGradoId]);
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
