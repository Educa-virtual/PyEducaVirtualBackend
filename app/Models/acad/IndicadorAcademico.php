<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class IndicadorAcademico
{
    public static function selIndicadoresParametros($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iYAcadId ?? NULL,
            $request->iIieeId ?? NULL,
            $request->iSedeId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_SEL_indicadoresParametros $placeholders", $parametros);
    }

    public static function selIndicadoresMatriculas($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iYAcadId ?? NULL,
            $request->iIieeId ?? NULL,
            $request->iSedeId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.Sp_SEL_indicadoresMatriculas $placeholders", $parametros);
    }

    public static function selIndicadoresDeserciones($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iYAcadId ?? NULL,
            $request->iIieeId ?? NULL,
            $request->iSedeId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.Sp_SEL_indicadoresDeserciones $placeholders", $parametros);
    }

    public static function selIndicadoresFaltasTardanzas($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iYAcadId ?? NULL,
            $request->iIieeId ?? NULL,
            $request->iSedeId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.sp_sel_indicadoresFaltasTardanzas $placeholders", $parametros);
    }

    public static function selIndicadoresDesempenos($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iYAcadId ?? NULL,
            $request->iIieeId ?? NULL,
            $request->iSedeId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.sp_sel_indicadoresDesempenos $placeholders", $parametros);
    }

    public static function selIndicadoresBajoRendimiento($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iYAcadId ?? NULL,
            $request->iIieeId ?? NULL,
            $request->iSedeId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.sp_sel_indicadoresBajoRendimiento $placeholders", $parametros);
    }
}