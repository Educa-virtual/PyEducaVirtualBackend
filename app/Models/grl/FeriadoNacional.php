<?php

namespace App\Models\grl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FeriadoNacional extends Model
{
    public static function selFeriadosNacionales(Object $request)
    {
        $parametros = [
                $request->header('iCredEntPerfId'),
                $request->iYAcadId ?? NULL,
            ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.Sp_SEL_feriadosNacionales $placeholders", $parametros);
    }

    public static function insFeriadoNacional(Object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId ?? NULL,
            $request->dtFeriado ?? NULL,
            $request->bFeriadoEsRecuperable ?? NULL,
            $request->cFeriadoNombre ?? NULL,
            $request->cFeriadoDescripcion ?? NULL,
            $request->cDocumento ?? NULL,
            $request->iEstado ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.Sp_INS_feriadoNacional $placeholders", $parametros);
    }

    public static function insFeriadoNacionalMasivo(Object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcidId ?? NULL,
            $request->jsonFeriadosNacionales ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.Sp_INS_feriadoNacionalMasivo $placeholders", $parametros);
    }

    public static function updFeriadoNacional(Object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iFeriadoId ?? NULL,
            $request->dtFeriado ?? NULL,
            $request->bFeriadoEsRecuperable ?? NULL,
            $request->cFeriadoNombre ?? NULL,
            $request->cFeriadoDescripcion ?? NULL,
            $request->cDocumento ?? NULL,
            $request->iEstado ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.Sp_UPD_feriadoNacional $placeholders", $parametros);
    }

    public static function delFeriadoNacional(Object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iFeriadoId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.Sp_DEL_feriadoNacional $placeholders", $parametros);
    }
}
