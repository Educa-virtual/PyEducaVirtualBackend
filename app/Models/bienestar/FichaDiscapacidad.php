<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;

class FichaDiscapacidad
{
    public static function selfichaDiscapacidad($request)
    {
        $parametros = [
            $request->iFichaDGId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_fichaDiscapacidad ' . $placeholders, $parametros);
    }

    public static function insFichaDiscapacidad($request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->bFichaDGEstaEnCONADIS,
            $request->cFichaDGCodigoCONADIS,
            $request->bFichaDGEstaEnOMAPED,
            $request->cFichaDGCodigoOMAPED,
            $request->bOtroProgramaDiscapacidad,
            $request->cOtroProgramaDiscapacidad,
            $request->jsonDiscapacidades,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_UPD_fichaDiscapacidad ' . $placeholders, $parametros);
    }

    public static function updFichaDiscapacidad($request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->bFichaDGEstaEnCONADIS,
            $request->cFichaDGCodigoCONADIS,
            $request->bFichaDGEstaEnOMAPED,
            $request->cFichaDGCodigoOMAPED,
            $request->bOtroProgramaDiscapacidad,
            $request->cOtroProgramaDiscapacidad,
            $request->jsonDiscapacidades,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_UPD_fichaDiscapacidad ' . $placeholders, $parametros);
    }
}
