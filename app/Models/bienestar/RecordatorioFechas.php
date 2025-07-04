<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;

class RecordatorioFechas
{
    public static function selCumpleanios($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_cumpleanios ' . $placeholders, $parametros);
    }

    public static function selRecordatorioPeriodos($request)
    {
        return DB::select('EXEC obe.Sp_SEL_recordatorioPeriodos');
    }

    public static function selCumpleaniosConfiguracion($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iPersId,
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_cumpleaniosConfiguracion ' . $placeholders, $parametros);
    }

    public static function updCumpleaniosConfiguracion($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iRecorPeriodoId,
            $request->iPersId,
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update('EXEC obe.Sp_UPD_cumpleaniosConfiguracion ' . $placeholders, $parametros);
    }
}