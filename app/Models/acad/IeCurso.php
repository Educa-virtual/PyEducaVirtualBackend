<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class IeCurso
{
    public static function selCursoPorIeCurso($ieCursoId)
    {
        return DB::selectOne('
            SELECT iCursoId, cng.iCursosNivelGradId
            FROM acad.ies_cursos AS ic
                INNER JOIN acad.cursos_niveles_grados cng ON
                    cng.iCursosNivelGradId=ic.iCursosNivelGradId
            WHERE ic.iIeCursoId=?', [$ieCursoId]);
    }

    public static function selIeCursos($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iConfigId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::select("EXEC acad.Sp_SEL_ieCursos $placeholders", $parametros);
    }

    public static function selIeCurso($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iIeCursoId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::selectOne("EXEC acad.Sp_SEL_ieCurso $placeholders", $parametros);
    }

    public static function insIeCurso($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iConfigId,
            $request->iProgId,
            $request->iCursosNivelGradId,
            $request->iHorasSemPresencial,
            $request->iHorasSemDomicilio,
            $request->iTotalHoras,
            $request->iConfPlanId,
            $request->iPorcentajeAporte,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::update("EXEC acad.Sp_INS_ieCurso $placeholders", $parametros);
    }

    public static function updIeCurso($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iConfigId,
            $request->iIeCursoId,
            $request->iCursosNivelGradId,
            $request->iHorasSemPresencial,
            $request->iHorasSemDomicilio,
            $request->iTotalHoras,
            $request->iConfPlanId,
            $request->iPorcentajeAporte,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::update("EXEC acad.Sp_UPD_ieCurso $placeholders", $parametros);
    }

    public static function updIeCursoEstado($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iIeCursoId,
            $request->bActivo,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::update("EXEC acad.Sp_UPD_ieCursoEstado $placeholders", $parametros);
    }
}
