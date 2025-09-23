<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FichaDiscapacidad
{
    public static function selfichaDiscapacidad($request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_fichaDiscapacidad ' . $placeholders, $parametros);
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
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_UPD_fichaDiscapacidad ' . $placeholders, $parametros);
    }
}
