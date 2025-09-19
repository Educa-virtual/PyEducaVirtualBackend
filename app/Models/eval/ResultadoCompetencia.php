<?php

namespace App\Models\eval;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ResultadoCompetencia extends Model
{
    public static function selResultadosPorCompetencia($iMatrId, $iCompetenciaId, $iCursosNivelGradId, $iPeriodoId)
    {
        return DB::selectOne("SELECT iMatrId, iPeriodoId, cNivelLogro, cDescripcion, iResultado
FROM eval.resultado_competencias AS rescom
INNER JOIN acad.detalle_matriculas AS detmat ON detmat.iDetMatrId=rescom.iDetMatrId
INNER JOIN acad.ies_cursos AS iecur ON iecur.iIeCursoId=detmat.iIeCursoId
WHERE rescom.iEstado=1 AND detmat.iMatrId=? AND iCompetenciaId=? AND iCursosNivelGradId=?
AND iPeriodoId=?", [$iMatrId, $iCompetenciaId, $iCursosNivelGradId, $iPeriodoId]);
    }
}
