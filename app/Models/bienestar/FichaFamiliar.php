<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;

class FichaFamiliar
{
    public static function selfichasFamiliaresPersonas($request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->iPersId,
            $request->iFamiliarId,
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_fichasFamiliaresPersonas ' . $placeholders, $parametros);
    }

    public static function insPersonas($request)
    {
        $parametros = [
            
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC grl.Sp_INS_personas ' . $placeholders, $parametros);
    }

    public static function insfichaFamiliar($request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->iPersId,
            $request->iTipoFamiliarId,
            $request->bFamiliarVivoConEl,
            $request->iTipoEstCivId,
            $request->iTipoViaId,
            $request->cFamiliarDireccionNombreVia,
            $request->cFamiliarDireccionNroPuerta,
            $request->cFamiliarDireccionBlock,
            $request->cFamiliarDireccionInterior,
            $request->iFamiliarDireccionPiso,
            $request->cFamiliarDireccionManzana,
            $request->cFamiliarDireccionLote,
            $request->cFamiliarDireccionKm,
            $request->cFamiliarDireccionReferencia,
            $request->iOcupacionId,
            $request->iGradoInstId,
            $request->iTipoIeEstId,
            $request->cTipoViaOtro,
            $request->cFamiliarResidenciaActual,
            $request->iTipoPersId || 1, // Siempre persona natural
            $request->iTipoIdentId,
            $request->cPersDocumento,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->cPersNombre,
            $request->cPersSexo,
            $request->dPersNacimiento,
            $request->cPersFotografia,
            $request->cPersRazonSocialNombre,
            $request->cPersRazonSocialCorto,
            $request->cPersRazonSocialSigla,
            $request->cPersDomicilio,
            $request->iSesionId,
            $request->iNacionId,
            $request->iPaisId,
            $request->iDptoId,
            $request->iPrvnId,
            $request->iDsttId,
            $request->cFamiliarTelefonoCelular,
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_INS_fichaFamiliar ' . $placeholders, $parametros);
    }

    public static function updFichaFamiliar($request)
    {
        $parametros = [
            $request->iFamiliarId,
            $request->iFichaDGId,
            $request->iPersId,
            $request->iTipoFamiliarId,
            $request->bFamiliarVivoConEl,
            $request->iTipoEstCivId,
            $request->iTipoViaId,
            $request->cFamiliarDireccionNombreVia,
            $request->cFamiliarDireccionNroPuerta,
            $request->cFamiliarDireccionBlock,
            $request->cFamiliarDireccionInterior,
            $request->iFamiliarDireccionPiso,
            $request->cFamiliarDireccionManzana,
            $request->cFamiliarDireccionLote,
            $request->cFamiliarDireccionKm,
            $request->cFamiliarDireccionReferencia,
            $request->iOcupacionId,
            $request->iGradoInstId,
            $request->iTipoIeEstId,
            $request->cTipoViaOtro,
            $request->cFamiliarResidenciaActual,
            $request->iTipoPersId || 1, // Siempre persona natural
            $request->iTipoIdentId,
            $request->cPersDocumento,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->cPersNombre,
            $request->cPersSexo,
            $request->dPersNacimiento,
            $request->cPersFotografia,
            $request->cPersRazonSocialNombre,
            $request->cPersRazonSocialCorto,
            $request->cPersRazonSocialSigla,
            $request->cPersDomicilio,
            $request->iSesionId,
            $request->iNacionId,
            $request->iPaisId,
            $request->iDptoId,
            $request->iPrvnId,
            $request->iDsttId,
            $request->cFamiliarTelefonoCelular,
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_UPD_fichaFamiliar ' . $placeholders, $parametros);
    }

    public static function selFichaFamiliar($request)
    {
        $parametros = [
            $request->iFamiliarId,
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_fichaFamiliar ' . $placeholders, $parametros);
    }

    public static function delFichaFamiliar($request)
    {
        $parametros = [
            $request->iFamiliarId,
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_DEL_fichaFamiliar ' . $placeholders, $parametros);
    }
}
