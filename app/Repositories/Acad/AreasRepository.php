<?php

namespace App\Repositories\Acad;

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
}
