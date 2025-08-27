<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;

class EncuestaBienestarPregunta
{
    public static function selPreguntas($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEncuId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC obe.Sp_SEL_encuestaPreguntas $placeholders", $parametros);
    }

    public static function insPregunta($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEncuId,
            $request->iEncuPregTipoId,
            $request->iEncuPregOrden,
            $request->cEncuPregContenido,
            $request->cEncuPregAdicional,
            $request->iEncuAlterGrupoId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC obe.Sp_INS_encuestaPregunta $placeholders", $parametros);
    }

    public static function updPregunta($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEncuId,
            $request->iEncuPregId,
            $request->iEncuPregTipoId,
            $request->iEncuPregOrden,
            $request->cEncuPregContenido,
            $request->cEncuPregAdicional,
            $request->iEncuAlterGrupoId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC obe.Sp_UPD_encuestaPregunta $placeholders", $parametros);
    }

    public static function selPregunta($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEncuPregId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC obe.Sp_SEL_encuestaPregunta $placeholders", $parametros);
    }

    public static function delPregunta($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEncuPregId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::delete("EXEC obe.Sp_DEL_encuestaPregunta $placeholders", $parametros);
    }
}
