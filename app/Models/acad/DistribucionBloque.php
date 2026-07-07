<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class DistribucionBloque
{
    public static function selDistribucionBloques($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.Sp_SEL_distribucionBloques $placeholders", $parametros);
    }

    public static function selDistribucionBloque($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iDistribucionBloqueId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_SEL_distribucionBloque $placeholders", $parametros);
    }

    public static function insDistribucionBloque($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId ?? NULL,
            $request->iTipoDistribucionId ?? NULL,
            $request->dtInicioBloque ?? NULL,
            $request->dtFinBloque ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_INS_DistribucionBloque $placeholders", $parametros);
    }

    public static function updDistribucionBloque($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iDistribucionBloqueId ?? NULL,
            $request->iTipoDistribucionId ?? NULL,
            $request->dtInicioBloque ?? NULL,
            $request->dtFinBloque ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_UPD_distribucionBloque $placeholders", $parametros);
    }
    
    public static function delDistribucionBloque($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iDistribucionBloqueId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_DEL_distribucionBloque $placeholders", $parametros);
    }
}