<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class CompetenciaCurso
{
    public static function selCursosPorIe($iSedeId, $iNivelGradoId)
    {
        return DB::select("SELECT iesc.iIeCursoId,acunig.iCursosNivelGradId,acur.iCursoId,iNivelTipoId,cCursoNombre,
(SELECT COUNT(compcur.iCompetenciaId)
FROM acad.competencias_cursos AS compcur
INNER JOIN acad.curriculo_competencias AS curcomp ON curcomp.iCompetenciaId=compcur.iCompetenciaId
WHERE compcur.iEstado=1 AND compcur.iNivelTipoId=nivgr.iNivelGradoId AND compcur.iCursoId=acur.iCursoId) AS iCantidadFilas
FROM acad.ies_cursos AS iesc
INNER JOIN acad.programas_estudios AS proge ON proge.iProgId=iesc.iProgId
INNER JOIN acad.cursos_niveles_grados AS acunig ON acunig.iCursosNivelGradId=iesc.iCursosNivelGradId
INNER JOIN acad.nivel_grados AS nivgr ON nivgr.iNivelGradoId=acunig.iNivelGradoId
INNER JOIN acad.nivel_ciclos AS nivcic ON nivcic.iNivelCicloId=nivgr.iNivelCicloId
INNER JOIN acad.cursos AS acur ON acur.iCursoId=acunig.iCursoId
WHERE iesc.iEstado=1 AND iSedeId=? AND nivgr.iNivelGradoId=?
ORDER BY cCursoNombre", [$iSedeId, $iNivelGradoId]);
    }

    public static function selCompetenciasPorCurso($iNivelTipoId, $iCursoId)
    {
        return DB::select("SELECT compcur.iCompetenciaId, cCompetenciaNombre
FROM acad.competencias_cursos AS compcur
INNER JOIN acad.curriculo_competencias AS curcomp ON curcomp.iCompetenciaId=compcur.iCompetenciaId
WHERE compcur.iEstado=1 AND compcur.iNivelTipoId=? AND compcur.iCursoId=?", [$iNivelTipoId, $iCursoId]);
    }
}
