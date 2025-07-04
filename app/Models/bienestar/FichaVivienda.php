<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;

class FichaVivienda
{
    public static function selfichaVivienda($request)
    {
        $parametros = [
            $request->iFichaDGId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_fichaVivienda ' . $placeholders, $parametros);
    }

    public static function insFichaVivienda($request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->iTipoOcupaVivId,
            $request->iMatPreId,
            $request->iTipoVivId,
            $request->iViviendaCarNroPisos,
            $request->iViviendaCarNroAmbientes,
            $request->iViviendaCarNroHabitaciones,
            $request->iEstadoVivId,
            $request->iMatPisoVivId,
            $request->iMatTecVivId,
            $request->iTiposSsHhId,
            $request->iTipoSumAId,
            $request->cTipoOcupaVivOtro,
            $request->cEstadoVivOtro,
            $request->cMatTecVivOtro,
            $request->cMatPisoVivOtro,
            $request->cMatPreOtro,
            $request->cTipoSumAOtro,
            $request->cTipoVivOtro,
            $request->cTipoSsHhOtro,
            $request->cTipoAlumOtro,
            $request->cEleParaVivOtro,
            $request->jsonAlumbrados,
            $request->jsonElementos,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_INS_fichaVivienda ' . $placeholders, $parametros);
    }

    public static function updFichaVivienda($request)
    {
        $parametros = [
            $request->iViendaCarId,
            $request->iFichaDGId,
            $request->iTipoOcupaVivId,
            $request->iMatPreId,
            $request->iTipoVivId,
            $request->iViviendaCarNroPisos,
            $request->iViviendaCarNroAmbientes,
            $request->iViviendaCarNroHabitaciones,
            $request->iEstadoVivId,
            $request->iMatPisoVivId,
            $request->iMatTecVivId,
            $request->iTiposSsHhId,
            $request->iTipoSumAId,
            $request->cTipoOcupaVivOtro,
            $request->cEstadoVivOtro,
            $request->cMatTecVivOtro,
            $request->cMatPisoVivOtro,
            $request->cMatPreOtro,
            $request->cTipoSumAOtro,
            $request->cTipoVivOtro,
            $request->cTipoSsHhOtro,
            $request->cTipoAlumOtro,
            $request->cEleParaVivOtro,
            $request->jsonAlumbrados,
            $request->jsonElementos,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_UPD_fichaVivienda ' . $placeholders, $parametros);
    }
}
