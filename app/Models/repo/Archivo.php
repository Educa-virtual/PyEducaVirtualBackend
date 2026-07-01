<?php

namespace App\Models\repo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Archivo extends Model
{
    public static function insArchivos($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iCarpetaId ?? NULL,
            $request->iPersId ?? NULL,
            $request->cNombreOriginal ?? NULL,
            $request->cNombre ?? NULL,
            $request->cExtension ?? NULL,
            $request->cRutaBase ?? NULL,
            $request->iTamano ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC repo.SP_INS_archivo $placeholders", $parametros);
    }

    public static function selArchivo($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iArchivoId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC repo.Sp_SEL_archivo $placeholders", $parametros);
    }

    public static function delArchivo($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iArchivoId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC repo.SP_DEL_archivo $placeholders", $parametros);
    }
}