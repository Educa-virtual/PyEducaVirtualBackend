<?php

namespace App\Models\acad;

use App\Helpers\VerifyHash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CalendarioAcademico extends Model
{
    public static function selCalendarioAcademicos($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iYAcadId ?? NULL,
            $request->iSedeId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_SEL_calendarioAcademicos $placeholders", $parametros);
    }

    public static function selCalendarioFechasInicioFinSede($iYAcadId, $iSedeId)
    {
        return DB::select("SELECT iPeriodoEvalAperId,calacad.iCalAcadId,cPeriodoEvalLetra,dtPeriodoEvalAperInicio, dtPeriodoEvalAperFin
        FROM acad.calendario_academicos AS calacad
        INNER JOIN acad.periodo_evaluaciones AS pereval ON pereval.iPeriodoEvalId=calacad.iPeriodoEvalId
        INNER JOIN acad.calendario_fases_promocionales AS calfasprom ON calfasprom.iCalAcadId=calacad.iCalAcadId
        INNER JOIN acad.calendario_periodos_evaluaciones AS calpereval ON calpereval.iFaseId=calfasprom.iFaseId
        WHERE calacad.iEstado=1 AND calacad.iYAcadId=? AND calacad.iSedeId=? AND iFasePromId=1
        ORDER BY dtPeriodoEvalAperInicio ASC", [$iYAcadId, $iSedeId]);
    }

    public static function selCalendarioAcademico(Request $request)
    {
        // Renombrar y mover a otro lado, es calendario de clases
        $parametros = [
            VerifyHash::decodes($request->iDocenteId),
            $request->iYAcadId,
            $request->iSedeId,
        ];
        $cantidad = str_repeat('?,', count($parametros) - 1).'?';
        return DB::selectOne("EXEC acad.Sp_SEL_calendarioAcademico ".$cantidad, $parametros);
    }
}
