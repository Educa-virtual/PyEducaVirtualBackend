<?php

namespace App\Models\grl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Persona extends Model
{
    public static function insPersonas(object $request)
    {
        $parametros = [
            $request->iTipoPersId ?? null,
            $request->iTipoIdentId ?? null,
            $request->cPersDocumento ?? null,
            $request->cPersPaterno ?? null,
            $request->cPersMaterno ?? null,
            $request->cPersNombre ?? null,
            $request->cPersSexo ?? null,
            $request->dPersNacimiento ?? null,
            $request->iTipoEstCivId ?? null,
            $request->cPersFotografia ?? null,
            $request->cPersRazonSocialNombre ?? null,
            $request->cPersRazonSocialCorto ?? null,
            $request->cPersRazonSocialSigla ?? null,
            $request->cPersDomicilio ?? null,
            $request->iCredId ?? null,
            $request->iNacionId ?? null,
            $request->iPaisId ?? null,
            $request->iDptoId ?? null,
            $request->iPrvnId ?? null,
            $request->iDsttId ?? null,
            $request->cPersTelefono ?? null,
            $request->cPersCorreo ?? null,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::select("EXEC grl.Sp_INS_personas $placeholders", $parametros);
    }

    public static function updPersonas(object $request)
    {
        $parametros = [
            $request->iPersId ?? null,
            $request->cPersDocumento ?? null,
            $request->cPersPaterno ?? null,
            $request->cPersMaterno ?? null,
            $request->cPersNombre ?? null,
            $request->cPersSexo ?? null,
            $request->dPersNacimiento ?? null,
            $request->iTipoEstCivId ?? null,
            $request->cPersFotografia ?? null,
            $request->cPersRazonSocialNombre ?? null,
            $request->cPersRazonSocialCorto ?? null,
            $request->cPersRazonSocialSigla ?? null,
            $request->cPersDomicilio ?? null,
            $request->iCredId ?? null,
            $request->iPersRepresentanteLegalId ?? null,
            $request->iNacionId ?? null,
            $request->iPaisId ?? null,
            $request->iDptoId ?? null,
            $request->iPrvnId ?? null,
            $request->iDsttId ?? null,
            $request->cPersTelefono ?? null,
            $request->cPersCorreo ?? null,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::select("EXEC grl.Sp_UPD_personas $placeholders", $parametros);
    }

    public static function selPersonaPorDocumento($documento)
    {
        return DB::selectOne('SELECT TOP 1 * FROM grl.personas WHERE cPersDocumento=?', [$documento]);
    }

    public static function updDatosPersonales($iPersId, Request $request)
    {
        return DB::update('UPDATE grl.personas SET cPersTelefono=?, cPersCorreo=?, cPersDomicilio=?, dtPersActualizado=GETDATE()
        WHERE iPersId=?', [$request->cPersTelefono, $request->cPersCorreo, $request->cPersDomicilio, $iPersId]);
    }

    public static function updFotoPerfil($iPersId, $foto)
    {
        DB::update('UPDATE grl.personas SET cPersFotografia=?, dtPersActualizado=GETDATE() WHERE iPersId=?', [$foto, $iPersId]);
    }
}
