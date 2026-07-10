<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class Merito
{
    public static function insMerito($request)
    {
        $parametros = [
            $request->iMeritoId ?? NULL,
            $request->iTipoMeritoId ?? NULL,
            $request->iPersId ?? NULL,
            $request->cMeritoDescripcion ?? NULL,
            $request->iMeritoPuntaje ?? NULL,
            $request->iMeritoPuesto ?? NULL,
            $request->cMeritoRef ?? NULL,
            $request->dtMeritoFecha ?? NULL,
            $request->iYAcadId ?? NULL,
            $request->iSedeId ?? NULL,
            $request->iCredEntPerfId ?? NULL,
        ];
        $placeholders = str_repeat('?,', count($parametros) - 1).'?';
        return DB::selectOne("EXEC acad.Sp_INS_merito $placeholders", $parametros);
    }
}