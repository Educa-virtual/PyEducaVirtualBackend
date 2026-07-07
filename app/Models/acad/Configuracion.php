<?php

namespace App\Models\acad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Configuracion extends Model
{
    public static function selConfiguracionParametros($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iConfigId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::selectOne("EXEC acad.Sp_SEL_configuracionParametros $placeholders", $parametros);
    }

    public static function selConfiguracion($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->iConfigId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::selectOne("EXEC acad.Sp_SEL_configuracion $placeholders", $parametros);
    }

    public static function insConfiguracion($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->iSedeId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::selectOne("EXEC acad.Sp_INS_configuracion $placeholders", $parametros);
    }

    public static function updConfiguracion($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iConfigId,
            $request->iEstadoConfigId,
            $request->cConfigNroRslAprobacion,
            $request->cConfigUrlRslAprobacion,
            $request->cConfigDescripcion,
            $request->bConfigEsBilingue,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::selectOne("EXEC acad.Sp_UPD_configuracion $placeholders", $parametros);
    }
}
