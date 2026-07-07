<?php

namespace App\Models\repo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Archivo extends Model
{
    public static function insArchivos($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? null,
            $request->iCarpetaId ?? null,
            $request->iPersId ?? null,
            $request->cNombreOriginal ?? null,
            $request->cNombre ?? null,
            $request->cExtension ?? null,
            $request->cRutaBase ?? null,
            $request->iTamano ?? null,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::selectOne("EXEC repo.SP_INS_archivo $placeholders", $parametros);
    }

    public static function selArchivo($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? null,
            $request->iArchivoId ?? null,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::selectOne("EXEC repo.Sp_SEL_archivo $placeholders", $parametros);
    }

    public static function delArchivo($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? null,
            $request->iArchivoId ?? null,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::selectOne("EXEC repo.SP_DEL_archivo $placeholders", $parametros);
    }
}
