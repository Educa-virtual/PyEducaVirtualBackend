<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CalendarioTurno extends Model
{
    public static function selCalendarioTurno(Request $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iCalAcadId ?? NULL,
            $request->iCalTurnoId ?? NULL,
            $request->iYAcadId ?? NULL,
            $request->iSedeId ?? NULL,

        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_SEL_calendarioTurno $placeholders", $parametros);
    }

    public static function insCalendarioTurno(Request $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iCalAcadId ?? NULL,
            $request->iTurnoId ?? NULL,
            $request->dtAperTurnoInicio ?? NULL,
            $request->dtAperTurnoFin ?? NULL,
            $request->jsonDiasLaborables ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_INS_calendarioTurno $placeholders", $parametros);
    }

    public static function updCalendarioTurno(Request $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iCalTurnoId ?? NULL,
            $request->iTurnoId ?? NULL,
            $request->dtAperTurnoInicio ?? NULL,
            $request->dtAperTurnoFin ?? NULL,
            $request->jsonDiasLaborables ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_UPD_calendarioTurno $placeholders", $parametros);
    }
}