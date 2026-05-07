<?php

namespace App\Models\grl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Persona extends Model
{
    public static function insPersonas(Object $request)
    {
        $parametros = [
            $request->iTipoPersId,
            $request->iTipoIdentId,
            $request->cPersDocumento,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->cPersNombre,
            $request->cPersSexo,
            $request->dPersNacimiento,
            $request->iTipoEstCivId,
            $request->cPersFotografia,
            $request->cPersRazonSocialNombre,
            $request->cPersRazonSocialCorto,
            $request->cPersRazonSocialSigla,
            $request->cPersDomicilio,
            $request->header('iCredId'),
            $request->iNacionId,
            $request->iPaisId,
            $request->iDptoId,
            $request->iPrvnId,
            $request->iDsttId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC grl.Sp_INS_personas $placeholders", $parametros);
    }

    public static function updPersonas(Object $request)
    {
        $parametros = [
            $request->iPersId,
            $request->cPersDocumento,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->cPersNombre,
            $request->cPersSexo,
            $request->dPersNacimiento,
            $request->iTipoEstCivId,
            $request->cPersFotografia,
            $request->cPersRazonSocialNombre,
            $request->cPersRazonSocialCorto,
            $request->cPersRazonSocialSigla,
            $request->cPersDomicilio,
            $request->header('iCredId'),
            $request->iPersRepresentanteLegalId,
            $request->iNacionId,
            $request->iPaisId,
            $request->iDptoId,
            $request->iPrvnId,
            $request->iDsttId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC grl.Sp_UPD_personas $placeholders", $parametros);
    }

    public static function selPersonaPorDocumento($documento)
    {
        return DB::selectOne("SELECT TOP 1 * FROM grl.personas WHERE cPersDocumento=?", [$documento]);
    }

    public static function updDatosPersonales($iPersId, Request $request)
    {
        return DB::update("UPDATE grl.personas SET cPersTelefono=?, cPersCorreo=?, cPersDomicilio=?, dtPersActualizado=GETDATE()
        WHERE iPersId=?", [$request->cPersTelefono, $request->cPersCorreo, $request->cPersDomicilio, $iPersId]);
    }

    public static function updFotoPerfil($iPersId, $foto) {
        DB::update("UPDATE grl.personas SET cPersFotografia=?, dtPersActualizado=GETDATE() WHERE iPersId=?", [$foto, $iPersId]);
    }
}
