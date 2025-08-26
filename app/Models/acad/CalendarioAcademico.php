<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CalendarioAcademico extends Model
{
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
}
