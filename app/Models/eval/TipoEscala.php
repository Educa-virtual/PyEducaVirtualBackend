<?php

namespace App\Models\eval;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TipoEscala extends Model
{
    public static function selTipoEscalas($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC eval.Sp_SEL_tipoEscalas $placeholders", $parametros);
    }

    public static function selTipoEscala($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iTipoEscalaId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC eval.Sp_SEL_tipoEscala $placeholders", $parametros);
    }

    public static function insTipoEscala($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->cTipoEscalaNombre ?? NULL,
            $request->cTipoEscalaResolucion ?? NULL,
            $request->cTipoEscalaAnio ?? NULL,
            $request->bHabilitado ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC eval.Sp_INS_tipoEscala $placeholders", $parametros);
    }

    public static function updTipoEscala($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iTipoEscalaId ?? NULL,
            $request->cTipoEscalaNombre ?? NULL,
            $request->cTipoEscalaResolucion ?? NULL,
            $request->cTipoEscalaAnio ?? NULL,
            $request->bHabilitado ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC eval.Sp_UPD_tipoEscala $placeholders", $parametros);
    }
}
