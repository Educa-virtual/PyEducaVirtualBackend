<?php

namespace App\Models\eval;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ResultadoCompetencia extends Model
{
    public static function selResultadosPorCompetencia($iMatrId, $iCompetenciaId, $iIeCursoId, $iPeriodoId)
    {
        return DB::selectOne("SELECT iMatrId, iPeriodoId, cNivelLogro, cDescripcion
FROM eval.resultado_competencias AS rescom
INNER JOIN acad.detalle_matriculas AS detmat ON detmat.iDetMatrId=rescom.iDetMatrId
WHERE rescom.iEstado=1 AND detmat.iMatrId=? AND iCompetenciaId=? AND iIeCursoId=?
AND iPeriodoId=?", [$iMatrId, $iCompetenciaId, $iIeCursoId, $iPeriodoId]);
    }
}
