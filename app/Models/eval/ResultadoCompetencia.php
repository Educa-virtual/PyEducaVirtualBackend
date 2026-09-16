<?php

namespace App\Models\eval;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResultadoCompetencia extends Model
{
    public static function selCursoEstudiantesCompetencias($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iYAcadId ?? NULL,
            $request->idDocCursoId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC eval.Sp_SEL_cursoEstudiantesCompetencias $placeholders", $parametros);
    }

    public static function selResultadosCompetencias($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iYAcadId ?? NULL,
            $request->iEstudianteId ?? NULL,
            $request->iPeriodoId ?? NULL,
            $request->iDetMatrId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC eval.Sp_SEL_resultadosCompetencias $placeholders", $parametros);
    }

    public static function updResultadosCompetencias($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iYAcadId ?? NULL,
            $request->iEstudianteId ?? NULL,
            $request->iCompCursoId ?? NULL,
            $request->iEscalaCalifId ?? NULL,
            $request->iPeriodoId ?? NULL,
            $request->cDescripcion ?? NULL,
            $request->iDetMatrId ?? NULL,
            $request->iResultadoCompId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC eval.Sp_UPD_resultadosCompetencias $placeholders", $parametros);
    }

    public static function selResultadosPorCompetencia($iMatrId, $iCompetenciaId, $iCursosNivelGradId, $iPeriodoId)
    {
        return DB::selectOne(" SELECT
            detmat.iMatrId,
            iPeriodoId,
            cNivelLogro,
            cDescripcion,
            iResultado
        FROM eval.resultado_competencias AS rescom
            INNER JOIN acad.detalle_matriculas AS detmat ON detmat.iDetMatrId=rescom.iDetMatrId
            INNER JOIN acad.cursos AS iecur ON iecur.iCursoId=detmat.iCursoId
            INNER JOIN acad.matricula mat ON mat.iMatrId=detmat.iMatrId
            INNER JOIN acad.cursos_niveles_grados AS cnig ON cnig.iNivelGradoId=mat.iNivelGradoId AND cnig.iCursoId=iecur.iCursoId
        WHERE
            rescom.iEstado=1 AND
            detmat.iMatrId=? AND
            iCompetenciaId=? AND
            iCursosNivelGradId=? AND
            iPeriodoId=?", [$iMatrId, $iCompetenciaId, $iCursosNivelGradId, $iPeriodoId]);
    }
}
