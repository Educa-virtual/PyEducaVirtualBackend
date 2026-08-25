<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class Sede
{
    public static function selSedes($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iIieeId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.Sp_SEL_sedes $placeholders", $parametros);
    }

    public static function insSede($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iIieeId ?? NULL,
            $request->cSedeNombre ?? NULL,
            $request->cSedeDireccion ?? NULL,
            $request->cSedeTelefono ?? NULL,
            $request->cSedeRslCreacion ?? NULL,
            $request->dtSedeRslCreacion ?? NULL,
            $request->iEstado ?? NULL,
            $request->iServEdId ?? NULL,
            $request->iTurnoId ?? NULL,
            $request->cEscNlat ?? NULL,
            $request->cEscNlog ?? NULL,
            $request->cEscDirector ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC acad.Sp_INS_sede $placeholders", $parametros);
    }

    public static function updSede($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iSedeId,
            $request->iIieeId ?? NULL,
            $request->cSedeNombre ?? NULL,
            $request->cSedeDireccion ?? NULL,
            $request->cSedeTelefono ?? NULL,
            $request->cSedeRslCreacion ?? NULL,
            $request->dtSedeRslCreacion ?? NULL,
            $request->iEstado ?? NULL,
            $request->iServEdId ?? NULL,
            $request->iTurnoId ?? NULL,
            $request->cEscNlat ?? NULL,
            $request->cEscNlog ?? NULL,
            $request->cEscDirector ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC acad.Sp_UPD_sede $placeholders", $parametros);
    }

    public static function delSede($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iSedeId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC acad.Sp_DEL_sede $placeholders", $parametros);
    }
}