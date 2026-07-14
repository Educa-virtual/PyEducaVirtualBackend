<?php

namespace App\Models\grl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FeriadoNacional extends Model
{
    public static function selFeriadosNacionales(Object $request)
    {
        $parametros = [
                $request->header('iCredEntPerfId'),
                $request->iYAcadId ?? NULL,
            ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC grl.Sp_SEL_feriadosNacionales $placeholders", $parametros);
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
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC grl.Sp_INS_feriadoNacional $placeholders", $parametros);
    }

    public static function insFeriadoNacionalMasivo(Object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId ?? NULL,
            $request->jsonFeriadosNacionales ?? NULL,
            $request->bPermiteSobrescribir ?? NULL,
        ];
        Log::info($parametros);
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC grl.Sp_INS_feriadoNacionalMasivo $placeholders", $parametros);
    }

    public static function updFeriadoNacional(Object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iFeriadoId ?? NULL,
            $request->bFeriadoEsRecuperable ?? NULL,
            $request->cFeriadoNombre ?? NULL,
            $request->cFeriadoDescripcion ?? NULL,
            $request->cDocumento ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC grl.Sp_UPD_feriadoNacional $placeholders", $parametros);
    }

    public static function delFeriadoNacional(Object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iFeriadoId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC grl.Sp_DEL_feriadoNacional $placeholders", $parametros);
    }

    public static function updFeriadoNacionalAplicar(Object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC grl.Sp_UPD_feriadoNacionalAplicar $placeholders", $parametros);
    }
}
