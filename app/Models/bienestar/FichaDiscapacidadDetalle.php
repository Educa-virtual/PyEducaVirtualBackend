<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;

class FichaDiscapacidadDetalle
{
    public static function selFichaDiscapacidadesDetalle($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iFichaDGId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_fichaDiscapacidadesDetalle ' . $placeholders, $parametros);
    }

    public static function selFichaDiscapacidadDetalle($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iDiscFichaId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne('EXEC obe.Sp_SEL_fichaDiscapacidadDetalle ' . $placeholders, $parametros);
    }

    public static function insFichaDiscapacidadDetalle($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iFichaDGId,
            $request->iDiscId,
            $request->cDiscFichaObs,
            $request->cDiscFichaArchivoNombre,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::insert('EXEC obe.Sp_INS_fichaDiscapacidadDetalle ' . $placeholders, $parametros);
    }

    public static function updFichaDiscapacidadDetalle($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iFichaDGId,
            $request->iDiscFichaId,
            $request->iDiscId,
            $request->cDiscFichaObs,
            $request->cDiscFichaArchivoNombre,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update('EXEC obe.Sp_UPD_fichaDiscapacidadDetalle ' . $placeholders, $parametros);
    }

    public static function borrarFichaDiscapacidadDetalle($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iDiscFichaId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne('EXEC obe.Sp_DEL_fichaDiscapacidadDetalle ' . $placeholders, $parametros);
    }
}
