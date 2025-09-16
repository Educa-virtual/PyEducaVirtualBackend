<?php

namespace App\Models\enc;

use Illuminate\Support\Facades\DB;

class Respuesta
{
    /**
     * Muestra todas las respuestas de una encuesta
     */
    public static function selRespuestas($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEncuId,
            $request->iYAcadId,
            $request->iNivelTipoId,
            $request->iTipoSectorId,
            $request->iZonaId,
            $request->iUgelId,
            $request->iDsttId,
            $request->iIieeId,
            $request->iNivelGradoId,
            $request->iSeccionId,
            $request->cPersSexo,
            $request->iPerfilId,
            $request->iCursoId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC enc.Sp_SEL_respuestas $placeholders", $parametros);
    }
    /**
     * Muestra las respuestas de una persona
     */
    public static function selRespuesta($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEncuId,
            $request->iPersId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_SEL_respuesta $placeholders", $parametros);
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
        return DB::selectResultSets("EXEC enc.Sp_SEL_respuestasDetalle $placeholders", $parametros);
    }
}
