<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;

class RecordatorioFechas
{
    public static function selCumpleanios($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        try {
            return DB::select('EXEC obe.Sp_SEL_cumpleanios ' . $placeholders, $parametros);
        } catch(\Exception $e) {
            // Manejar error en caso de que no se devuelva ningún resultado
            if (str_contains($e->getMessage(), 'contains no fields')) {
                return [];
            }
            throw $e;
        }
    }

    public static function selRecordatorioPeriodos($request)
    {
        return DB::select('EXEC obe.Sp_SEL_recordatorioPeriodos');
    }

    public static function selCumpleaniosConfiguracion($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPersId,
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_cumpleaniosConfiguracion ' . $placeholders, $parametros);
    }

    public static function updCumpleaniosConfiguracion($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iRecorPeriodoId,
            $request->iPersId,
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update('EXEC obe.Sp_UPD_cumpleaniosConfiguracion ' . $placeholders, $parametros);
    }
}