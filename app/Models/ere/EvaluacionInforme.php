<?php

namespace App\Models\ere;

use Illuminate\Support\Facades\DB;

class EvaluacionInforme
{
    public static function selEvaluacionesInformeOpt($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->iEvaluacionId,
            $request->iCursoNivelGradoId,
            $request->iIieeId,
            $request->iDsttId,
            $request->iUgelId,
            $request->iNivelTipoId,
            $request->iNivelGradoId,
            $request->iSeccionId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC ere.SP_SEL_evaluacionesInformeOpt $placeholders", $parametros);
    }

    public static function selEvaluacionInformeResumenOpt($request)
    {
        $parametros = [
            $request->iYAcadId,
            $request->iEvaluacionId,
            $request->iCursoId,
            $request->iNivelTipoId,
            $request->iNivelGradoId,
            $request->iSeccionId,
            $request->iDsttId,
            $request->cPersSexo,
            $request->iUgelId,
            $request->iIieeId,
            $request->iSedeId,
            $request->iTipoSectorId,
            $request->iZonaId,
            $request->header('iCredEntPerfId'),
            $request->bMostrarDetalle,
            $request->cTipoReporte,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC ere.SP_SEL_evaluacionInformeResumenOpt $placeholders", $parametros);
    }

    public static function selEvaluacionInformeComparacion($request)
    {
        $parametros = [
            $request->iYAcadId,
            $request->iEvaluacion1,
            $request->iEvaluacion2,
            $request->iCursoId,
            $request->iNivelTipoId,
            $request->iNivelGradoId,
            $request->iSeccionId,
            $request->iDsttId,
            $request->cPersSexo,
            $request->iUgelId,
            $request->iIieeId,
            $request->iSedeId,
            $request->iTipoSectorId,
            $request->iZonaId,
            $request->header('iCredEntPerfId'),
            $request->bMostrarDetalle ?? null,
            $request->cTipoReporte ?? null,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC ere.SP_SEL_evaluacionInformeComparacion $placeholders", $parametros);
    }
}
