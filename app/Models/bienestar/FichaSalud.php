<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;

class FichaSalud
{
    public static function selfichaSalud($request)
    {
        $parametros = [
            $request->iFichaDGId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_fichaSalud ' . $placeholders, $parametros);
    }

    public static function insFichaSalud($request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->bFichaDGAlergiaMedicamentos,
            $request->cFichaDGAlergiaMedicamentos,
            $request->bFichaDGAlergiaOtros,
            $request->cFichaDGAlergiaOtros,
            $request->jsonSeguros,
            $request->jsonDolencias,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_UPD_fichaSalud ' . $placeholders, $parametros);
    }

    public static function updFichaSalud($request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->bFichaDGAlergiaMedicamentos,
            $request->cFichaDGAlergiaMedicamentos,
            $request->bFichaDGAlergiaOtros,
            $request->cFichaDGAlergiaOtros,
            $request->jsonSeguros,
            $request->jsonDolencias,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_UPD_fichaSalud ' . $placeholders, $parametros);
    }
}
