<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;

class FichaEconomico
{
    public static function selFichaEconomico($request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_fichaEconomico ' . $placeholders, $parametros);
    }

    public static function updFichaEconomico($request)
    {
        $parametros = [
            $request->iIngresoEcoId,
            $request->iFichaDGId,
            $request->iIngresoEcoFamiliar,
            $request->cIngresoEcoActividad,
            $request->iIngresoEcoEstudiante,
            $request->iIngresoEcoTrabajoHoras,
            $request->bIngresoEcoTrabaja,
            $request->cIngresoEcoDependeDe,
            $request->iRangoSueldoId,
            $request->iRangoSueldoIdPersona,
            $request->iDepEcoId,
            $request->iTipoAEcoId,
            $request->iJorTrabId,
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_UPD_fichaEconomico ' . $placeholders, $parametros);
    }
}
