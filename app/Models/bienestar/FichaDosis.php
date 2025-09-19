<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;

class FichaDosis
{
    public static function selFichasDosis($request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->iPandemiaId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_fichasDosis ' . $placeholders, $parametros);
    }

    public static function selFichaDosis($request)
    {
        $parametros = [
            $request->iPanDFichaId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_fichaDosis ' . $placeholders, $parametros);
    }

    public static function insFichaDosis($request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->iPandemiaId,
            $request->iPanDFichaNroDosis,
            $request->dtPanDFichaDosis,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::insert('EXEC obe.Sp_INS_fichaDosis ' . $placeholders, $parametros);
    }

    public static function updFichaDosis($request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->iPanDFichaId,
            $request->iPandemiaId,
            $request->iPanDFichaNroDosis,
            $request->dtPanDFichaDosis,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update('EXEC obe.Sp_UPD_fichaDosis ' . $placeholders, $parametros);
    }

    public static function borrarFichaDosis($request)
    {
        $parametros = [
            $request->iPanDFichaId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::delete('EXEC obe.Sp_DEL_fichaDosis ' . $placeholders, $parametros);
    }
}
