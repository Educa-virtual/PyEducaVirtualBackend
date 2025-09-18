<?php

namespace App\Models\grl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Persona extends Model
{
    public static function selPersonaPorDocumento($documento)
    {
        return DB::selectOne("SELECT TOP 1 * FROM grl.personas WHERE cPersDocumento=?", [$documento]);
    }

    public static function updDatosPersonales($iPersId, Request $request)
    {
        return DB::update("UPDATE grl.personas SET cPersTelefono=?, cPersCorreo=?, cPersDomicilio=?, dtPersActualizado=GETDATE()
        WHERE iPersId=?", [$request->cPersTelefono, $request->cPersCorreo, $request->cPersDomicilio, $iPersId]);
    }
}
