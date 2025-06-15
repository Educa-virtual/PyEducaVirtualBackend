<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;

class Ficha
{
    public static function selfichaParametros()
    {
        return DB::select('EXEC obe.Sp_SEL_fichaParametros');
    }

    public static function selfichas($request)
    {
        $parametros = [
            $request->iCredSesionId,
            $request->iFichaDGId,
            $request->iPersId,
            $request->cPersDocumento,
            $request->cPersNombresApellidos,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_fichas ' . $placeholders, $parametros);
    }

    public static function selfichasApoderado($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_fichasApoderado ' . $placeholders, $parametros);
    }

    public static function selficha($request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->iPersId,
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_ficha ' . $placeholders, $parametros);
    }

    public static function insFicha($request)
    {
        $parametros = [
            $request->iPersId,
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_INS_ficha ' . $placeholders, $parametros);
    }

    public static function delFicha($request)
    {
        $parametros = [
            $request->iFichaDGId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_DEL_ficha ' . $placeholders, $parametros);
    }
}
