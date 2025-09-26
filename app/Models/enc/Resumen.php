<?php

namespace App\Models\enc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Resumen extends Model
{
    public static function selResumen($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEncuId,
            $request->iPregId,
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
            $request->iPerfilId,
            $request->iCursoId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC enc.Sp_SEL_resumen $placeholders", $parametros);
    }
}
