<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;

class EncuestaBienestar
{
    public static function selEncuestas($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC obe.Sp_SEL_encuestas $placeholders", $parametros);
    }

    public static function selEncuestaParametros($request)
    {
        return DB::select('EXEC obe.Sp_SEL_encuestaParametros');
    }

    public static function insEncuesta($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->cEncuNombre,
            $request->cEncuDescripcion,
            $request->dEncuDesde,
            $request->cEncuHasta,
            $request->iCategoriaId,
            $request->jsonPermisos,
            $request->jsonPoblacion,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::insert("EXEC obe.Sp_INS_encuesta $placeholders", $parametros);
    }

    public static function updEncuesta($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEncuId,
            $request->cEncuNombre,
            $request->cEncuDescripcion,
            $request->dEncuDesde,
            $request->cEncuHasta,
            $request->iCategoriaId,
            $request->jsonPoblacion,
            $request->jsonPermisos,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC obe.Sp_UPD_encuesta $placeholders", $parametros);
    }

    public static function selEncuesta($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEncuId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC obe.Sp_SEL_encuesta $placeholders", $parametros);
    }

    public static function delEncuesta($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEncuId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::delete("EXEC obe.Sp_DEL_encuesta $placeholders", $parametros);
    }

    public static function selPoblacionObjetivo($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->jsonPoblacion,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC obe.Sp_SEL_poblacion_objetivo $placeholders", $parametros);
    }

    
}
