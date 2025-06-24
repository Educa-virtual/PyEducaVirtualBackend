<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;

class EncuestaBienestarRespuesta
{
    public static function selRespuestas($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEncuId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC obe.Sp_SEL_encuestaRespuestas $placeholders", $parametros);
    }

    public static function insRespuesta($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEncuPregId,
            $request->iEncuRpta,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC obe.Sp_INS_encuestaRespuesta $placeholders", $parametros);
    }

    public static function updRespuesta($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEncuPregId,
            $request->cEncuRptaContenido,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC obe.Sp_UPD_encuestaRespuesta $placeholders", $parametros);
    }

    public static function selRespuesta($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->cEncuRptaContenido,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC obe.Sp_SEL_encuestaRespuesta $placeholders", $parametros);
    }

    public static function delRespuesta($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEncuPregId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::delete("EXEC obe.Sp_DEL_encuestaRespuesta $placeholders", $parametros);
    }
}
