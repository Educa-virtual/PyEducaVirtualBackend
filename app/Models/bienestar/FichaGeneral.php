<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;

class FichaGeneral
{
    public static function selfichaGeneral($request)
    {
        $parametros = [
            $request->iFichaDGId
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_fichaGeneral ' . $placeholders, $parametros);
    }

    public static function updFichaGeneral($request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->iTipoViaId,
            $request->cFichaDGDireccionNombreVia,
            $request->cFichaDGDireccionNroPuerta,
            $request->cFichaDGDireccionBlock,
            $request->cFichaDGDireccionInterior,
            $request->cFichaDGDireccionPiso,
            $request->cFichaDGDireccionManzana,
            $request->cFichaDGDireccionLote,
            $request->cFichaDGDireccionKm,
            $request->cFichaDGDireccionReferencia,
            $request->iReligionId,
            $request->bFamiliarPadreVive,
            $request->bFamiliarMadreVive,
            $request->bFamiliarPadresVivenJuntos,
            $request->bFichaDGTieneHijos,
            $request->iFichaDGNroHijos,
            $request->cTipoViaOtro,
            $request->cReligionOtro,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_UPD_fichaGeneral ' . $placeholders, $parametros);
    }
}
