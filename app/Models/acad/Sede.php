<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class Sede
{
    public static function selSedes($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iIieeId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.Sp_SEL_sedes $placeholders", $parametros);
    }

    public static function insSede($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iIieeId,
            $request->cNombre,
            $request->cDireccion,
            $request->cTelefono,
            $request->cCelular,
            $request->cEmail,
            $request->cFax,
            $request->cWeb,
            $request->iTipoId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC acad.Sp_INS_sede $placeholders", $parametros);
    }

    public static function updSede($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iSedeId,
            $request->cNombre,
            $request->cDireccion,
            $request->cTelefono,
            $request->cCelular,
            $request->cEmail,
            $request->cFax,
            $request->cWeb,
            $request->iTipoId,
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