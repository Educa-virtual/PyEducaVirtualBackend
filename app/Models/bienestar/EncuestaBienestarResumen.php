<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;

class EncuestaBienestarResumen
{
    public static function verResumen($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEncuId,
            $request->iEncuPregId,
            $request->iTipoReporte,
            $request->iLimitePalabras,
            $request->jsonEtiquetas,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC obe.Sp_SEL_encuestaResumen $placeholders", $parametros);
    }
}
