<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EncuestaBienestar
{
    public static function selEncuestas($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        try {
            return DB::select("EXEC obe.Sp_SEL_encuestas $placeholders", $parametros);
        } catch(\Exception $e) {
            // Manejar error en caso de que no se devuelva ningún resultado
            if (str_contains($e->getMessage(), 'contains no fields')) {
                return [];
            }
            throw $e;
        }
    }

    public static function selEncuestaParametros($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC obe.Sp_SEL_encuestaParametros $placeholders", $parametros);
    }

    public static function insEncuesta($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->cEncuNombre,
            $request->cEncuDescripcion,
            $request->dEncuDesde,
            $request->dEncuHasta,
            $request->iEncuCateId,
            $request->jsonPoblacion,
            $request->jsonPermisos,
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC obe.Sp_INS_encuesta $placeholders", $parametros);
    }

    public static function updEncuesta($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEncuId,
            $request->cEncuNombre,
            $request->cEncuDescripcion,
            $request->dEncuDesde,
            $request->dEncuHasta,
            $request->iEncuCateId,
            $request->jsonPoblacion,
            $request->jsonPermisos,
            $request->iYAcadId,
            $request->iEstado,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC obe.Sp_UPD_encuesta $placeholders", $parametros);
    }

    public static function updEncuestaEstado($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEncuId,
            $request->iEstado,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC obe.Sp_UPD_encuestaEstado $placeholders", $parametros);
    }

    public static function selEncuesta($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEncuId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC obe.Sp_SEL_encuesta $placeholders", $parametros);
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
            $request->iYAcadId,
            $request->jsonPoblacion,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC obe.Sp_SEL_encuestaPoblacion $placeholders", $parametros);
    }


}
