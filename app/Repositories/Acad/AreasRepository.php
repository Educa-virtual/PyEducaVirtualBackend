<?php

namespace App\Repositories\acad;

use Illuminate\Support\Facades\DB;

class AreasRepository
{
    public static function obtenerAreaPorNivelGradId($iCursosNivelGradId)
    {
        $area = DB::selectOne('EXEC [ere].[SP_SEL_CursoNivelGrado] @_iCursoNivelGrado=?', [$iCursosNivelGradId]);
        return $area;
    }

    public static function obtenerMatrizPorEvaluacionArea($iEvaluacionId, $iCursosNivelGradId)
    {
        return DB::select('EXEC ere.Sp_SEL_MatrizEvaluacion @_iEvaluacionId=?, @_iCursosNivelGradId=?', [$iEvaluacionId, $iCursosNivelGradId]);
    }

    public static function liberarAreasPorEvaluacion($iEvaluacionId)
    {
        return DB::statement('EXEC [ere].[SP_UPD_CursosEvaluacionLiberacion] @iEvaluacionId=?', [$iEvaluacionId]);
    }

    public static function obtenerHorasAreasPorEvaluacionIe($evaluacionId, $iieeId) {
        return DB::select("SELECT iece.iIeeCursoExamenId,ec.iExamCurId, iepe.iIeeParticipaId,ec.iExamCurId,ec.iCursoNivelGradId, ec.dtExamenFechaInicio,
ec.dtExamenFechaFin, c.cCursoNombre, g.cGradoAbreviacion, g.cGradoNombre, nt.cNivelTipoNombre, tInicio, tFin
 FROM ere.examen_cursos ec
 INNER JOIN acad.cursos_niveles_grados cng ON ec.iCursoNivelGradId = cng.iCursosNivelGradId
 INNER JOIN acad.cursos c ON cng.iCursoId = c.iCursoId
 INNER JOIN acad.nivel_grados ng ON ng.iNivelGradoId = cng.iNivelGradoId
 INNER JOIN acad.grados g ON g.iGradoId = ng.iGradoId
 INNER JOIN acad.nivel_ciclos nc ON nc.iNivelCicloId = ng.iNivelCicloId
 INNER JOIN acad.nivel_tipos nt ON nc.iNivelTipoId = nt.iNivelTipoId
 INNER JOIN ere.iiee_participa_evaluaciones AS iepe ON iepe.iEvaluacionId=ec.iEvaluacionId
 LEFT JOIN ere.iiee_cursos_examen AS iece ON iepe.iIeeParticipaId=iece.iIeeParticipaId AND iece.iExamCurId=ec.iExamCurId
 WHERE ec.iEvaluacionId = ? AND iepe.iIieeId = ?", [$evaluacionId, $iieeId]);
    }

    public static function eliminarHorasAreasPorEvaluacionIe($iIeeParticipaId) {
        return DB::statement("DELETE FROM [ere].[iiee_cursos_examen] WHERE iIeeParticipaId=?", [$iIeeParticipaId]);
    }

    public static function registrarHorasAreasPorEvaluacionIe($params, $horaInicio, $horaFin) {
        DB::statement("INSERT INTO [ere].[iiee_cursos_examen]
           ([tInicio],[tFin],[iIeeParticipaId]
           ,[iExamCurId],[iEstado],[iSesionId],[dtCreado],[dtActualizado])
     VALUES (?,?,?,?,?,?,GETDATE(),GETDATE())",[$horaInicio, $horaFin, $params['iIeeParticipaId'], $params['iExamCurId'], 1, 1]);
    }
}
