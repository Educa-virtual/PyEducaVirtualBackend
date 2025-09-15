<?php

namespace App\Models\enc;

use Illuminate\Support\Facades\DB;

class Respuesta
{
    public static function selRespuestas($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEncuId,
            $request->iPersId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_SEL_respuestas $placeholders", $parametros);
    }

    public static function insRespuestas($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->iEncuId,
            $request->jsonPreguntas,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC enc.Sp_INS_respuesta $placeholders", $parametros);
    }

    public static function updRespuestas($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->iEncuId,
            $request->jsonPreguntas,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC enc.Sp_UPD_respuesta $placeholders", $parametros);
    }

    public static function selRespuestasDetalle($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEncuId,
            $request->iPersId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectResultSets("EXEC enc.Sp_SEL_encuestaRespuestasDetalle $placeholders", $parametros);
    }
}
