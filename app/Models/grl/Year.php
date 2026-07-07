<?php

namespace App\Models\grl;

use Illuminate\Support\Facades\DB;

class Year
{
    public static function selYearParametros($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC grl.Sp_SEL_yearParametros $placeholders", $parametros);
    }

    public static function selYears($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC grl.Sp_SEL_years $placeholders", $parametros);
    }

    public static function selYear($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYearId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC grl.Sp_SEL_year $placeholders", $parametros);
    }

    public static function insYear($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->cYearNombre ?? NULL,
            $request->cYearOficial ?? NULL,
            $request->iYearEstado ?? NULL,
            $request->dtYAcadInicio ?? NULL,
            $request->dYAcadFin ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC grl.Sp_INS_year $placeholders", $parametros);
    }

    public static function updYear($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYearId ?? NULL,
            $request->cYearNombre ?? NULL,
            $request->cYearOficial ?? NULL,
            $request->iYearEstado ?? NULL,
            $request->dtYAcadInicio ?? NULL,
            $request->dYAcadFin ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC grl.Sp_UPD_year $placeholders", $parametros);
    }
    
    public static function delYear($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYearId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC grl.Sp_DEL_year $placeholders", $parametros);
    }
}