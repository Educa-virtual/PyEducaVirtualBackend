<?php

namespace App\Models\acad;

use App\Helpers\VerifyHash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarioAcademico extends Model
{
    public static function selCalendarioAcademicosxiYAcadIdxiSedeId($request)
    {
        $parametros = [
            $request->iYAcadId ?? NULL,
            $request->iSedeId ?? NULL,
            $request->header('iCredId') ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.Sp_SEL_calendarioAcademicosxiYAcadIdxiSedeId $placeholders", $parametros);
    }

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
        return DB::select('SELECT iPeriodoEvalAperId,calacad.iCalAcadId,cPeriodoEvalLetra,dtPeriodoEvalAperInicio, dtPeriodoEvalAperFin
        FROM acad.calendario_academicos AS calacad
        INNER JOIN acad.periodo_evaluaciones AS pereval ON pereval.iPeriodoEvalId=calacad.iPeriodoEvalId
        INNER JOIN acad.calendario_fases_promocionales AS calfasprom ON calfasprom.iCalAcadId=calacad.iCalAcadId
        INNER JOIN acad.calendario_periodos_evaluaciones AS calpereval ON calpereval.iFaseId=calfasprom.iFaseId
        WHERE calacad.iEstado=1 AND calacad.iYAcadId=? AND calacad.iSedeId=? AND iFasePromId=1
        ORDER BY dtPeriodoEvalAperInicio ASC', [$iYAcadId, $iSedeId]);
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

    public static function insCalendarioAcademicos($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId ?? NULL,
            $request->iSedeId ?? NULL,
            $request->dtCalAcadInicio ?? NULL,
            $request->dtCalAcadFin ?? NULL,
            $request->iPeriodoEvalId ?? NULL,
            $request->dtCalAcadMatriculaInicio ?? NULL,
            $request->dtCalAcadMatriculaFin ?? NULL,
            $request->dtCalAcadMatriculaResagados ?? NULL,
            $request->dtFaseInicioRegular ?? NULL,
            $request->dtFaseFinRegular ?? NULL,
            $request->dtFaseInicioRecuperacion ?? NULL,
            $request->dtFaseFinRecuperacion ?? NULL,
            $request->iTurnoId ?? NULL,
            $request->dtAperTurnoInicio ?? NULL,
            $request->dtAperTurnoFin ?? NULL,
            $request->jsonDiasLaborables ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_INS_calendarioAcademicos $placeholders", $parametros);
    }

    public static function updCalendarioAcademicos($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iCalAcadId ?? NULL,
            $request->dtCalAcadInicio ?? NULL,
            $request->dtCalAcadFin ?? NULL,
            $request->iPeriodoEvalId ?? NULL,
            $request->dtCalAcadMatriculaInicio ?? NULL,
            $request->dtCalAcadMatriculaFin ?? NULL,
            $request->dtCalAcadMatriculaResagados ?? NULL,
            $request->dtFaseInicioRegular ?? NULL,
            $request->dtFaseFinRegular ?? NULL,
            $request->dtFaseInicioRecuperacion ?? NULL,
            $request->dtFaseFinRecuperacion ?? NULL,
            $request->iTurnoId ?? NULL,
            $request->dtAperTurnoInicio ?? NULL,
            $request->dtAperTurnoFin ?? NULL,
            $request->jsonDiasLaborables ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_UPD_calendarioAcademicos $placeholders", $parametros);
    }
}
