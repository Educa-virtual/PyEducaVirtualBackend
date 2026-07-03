<?php

namespace App\Models\eval;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResultadoCompetencia extends Model
{
    public static function selResultadosPorCompetencia($iMatrId, $iCompetenciaId, $iCursosNivelGradId, $iPeriodoId)
    {
        return DB::selectOne("SELECT detmat.iMatrId, iPeriodoId, cNivelLogro, cDescripcion, iResultado
FROM eval.resultado_competencias AS rescom
    INNER JOIN acad.detalle_matriculas AS detmat ON detmat.iDetMatrId=rescom.iDetMatrId
    INNER JOIN acad.cursos AS iecur ON iecur.iCursoId=detmat.iCursoId
    INNER JOIN acad.matricula mat ON mat.iMatrId=detmat.iMatrId
    INNER JOIN acad.cursos_niveles_grados AS cnig ON cnig.iNivelGradoId=mat.iNivelGradoId AND cnig.iCursoId=iecur.iCursoId
WHERE rescom.iEstado=1 AND detmat.iMatrId=? AND iCompetenciaId=? AND iCursosNivelGradId=?
AND iPeriodoId=?", [$iMatrId, $iCompetenciaId, $iCursosNivelGradId, $iPeriodoId]);
    }
}
