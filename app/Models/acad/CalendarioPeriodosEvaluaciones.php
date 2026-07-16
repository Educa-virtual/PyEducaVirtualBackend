<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CalendarioPeriodosEvaluaciones extends Model
{
    public static function selCalendariosPeriodosEvaluaciones(Request $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iYAcadId ?? NULL,
            $request->iCalAcadId ?? NULL,
            $request->iSedeId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.Sp_SEL_calendarioPeriodosEvaluaciones $placeholders", $parametros);
    }

    public static function updCalendarioPeriodosAcademicos(Request $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iCalAcadId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.SP_UPD_calendarioPeriodosAcademicos $placeholders", $parametros);
    }

    public static function updCalendarioPeriodoAcademico(Request $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iPeriodoEvalAperId,
            $request->dtPeriodoEvalAperFin,
            $request->dtPeriodoEvalAperInicio,
            $request->bHabilitado,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.Sp_UPD_calendarioPeriodoAcademico $placeholders", $parametros);
    }
}
