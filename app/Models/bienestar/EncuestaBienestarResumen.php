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
            $request->iNivelTipoId,
            $request->iTipoSectorId,
            $request->iZonaId,
            $request->iUgelId,
            $request->iDsttId,
            $request->iIieeId,
            $request->iNivelGradoId,
            $request->iSeccionId,
            $request->cPersSexo,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC obe.Sp_SEL_encuestaResumen $placeholders", $parametros);
    }
}
