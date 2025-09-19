<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;

class FichaRecreacion
{
    public static function selFichaRecreacion($request)
    {
        $parametros = [
            $request->iFichaDGId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_fichaRecreacion ' . $placeholders, $parametros);
    }

    public static function updFichaRecreacion($request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->cFichaDGPerteneceLigaDeportiva,
            $request->cFichaDGPerteneceCentroArtistico,
            $request->cFichaDGAsistioConsultaPsicologica,
            $request->iReligionId,
            $request->cReligionOtro,
            $request->cDeporteOtro,
            $request->cTransporteOtro,
            $request->cPasaTiempoOtro,
            $request->cActArtisticaOtro,
            $request->iEstadoRelFamiliar,
            $request->jsonDeportes,
            $request->jsonTransportes,
            $request->jsonPasatiempos,
            $request->jsonProblemas,
            $request->iLenguaId,
            $request->cLenguaOtro,
            $request->iEtniaId,
            $request->cEtniaOtro,
            $request->jsonLenguas,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_UPD_fichaRecreacion ' . $placeholders, $parametros);
    }
}
