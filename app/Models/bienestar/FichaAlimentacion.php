<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;

class FichaAlimentacion
{
    public static function selfichaAlimentacion($request)
    {
        $parametros = [
            $request->iFichaDGId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_fichaAlimentacion ' . $placeholders, $parametros);
    }

    public static function updfichaAlimentacion($request)
    {
        $parametros = [
            $request->iAlimId,
            $request->iFichaDGId,
            $request->iLugarAlimIdDesayuno,
            $request->cLugarAlimDesayuno,
            $request->iLugarAlimIdAlmuerzo,
            $request->cLugarAlimAlmuerzo,
            $request->iLugarAlimIdCena,
            $request->cLugarAlimCena,
            $request->bDietaEspecial,
            $request->cDietaEspecialObs,
            $request->bFichaDGAlergiaAlimentos,
            $request->cFichaDGAlergiaAlimentos,
            $request->bIntoleranciaAlim,
            $request->cIntoleranciaAlimObs,
            $request->bSumplementosAlim,
            $request->cSumplementosAlimObs,
            $request->bDificultadAlim,
            $request->cDificultadAlimObs,
            $request->cAlimObs,
            $request->jsonProgramas,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_UPD_fichaAlimentacion ' . $placeholders, $parametros);
    }
}
