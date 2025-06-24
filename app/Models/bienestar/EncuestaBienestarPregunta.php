<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;

class EncuestaBienestarPregunta
{
    public static function selPreguntas($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEncuId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC obe.Sp_SEL_encuestaPreguntas $placeholders", $parametros);
    }

    public static function insPregunta($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEncuId,
            $request->iEncuPregTipoId,
            $request->cEncuPregOrden,
            $request->cEncuPregContenido,
            $request->cEncuPregAdicional,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC obe.Sp_INS_encuestaPregunta $placeholders", $parametros);
    }

    public static function updPregunta($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEncuId,
            $request->iEncuPregId,
            $request->iEncuPregTipoId,
            $request->cEncuPregOrden,
            $request->cEncuPregContenido,
            $request->cEncuPregAdicional,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC obe.Sp_UPD_encuestaPregunta $placeholders", $parametros);
    }

    public static function selPregunta($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEncuPregId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC obe.Sp_SEL_encuestaPregunta $placeholders", $parametros);
    }

    public static function delPregunta($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEncuPregId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::delete("EXEC obe.Sp_DEL_encuestaPregunta $placeholders", $parametros);
    }
}
