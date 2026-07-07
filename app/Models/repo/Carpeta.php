<?php

namespace App\Models\repo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Carpeta extends Model
{
    public static function selCarpetas($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? null,
            $request->iCarpetaId ?? null,
            $request->iPersId ?? null,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::select("EXEC repo.SP_SEL_carpetas $placeholders", $parametros);
    }

    public static function selCarpetasReporte($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? null,
            $request->iPersId ?? null,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::select("EXEC repo.SP_SEL_carpetasReporte $placeholders", $parametros);
    }

    public static function selCarpeta($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? null,
            $request->iCarpetaId ?? null,
            $request->iPersId ?? null,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::selectOne("EXEC repo.SP_SEL_carpeta $placeholders", $parametros);
    }

    public static function insCarpeta($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? null,
            $request->cNombre ?? null,
            $request->iPersId ?? null,
            $request->iParentCarpetaId ?? null,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::selectOne("EXEC repo.SP_INS_carpeta $placeholders", $parametros);
    }

    public static function updCarpeta($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? null,
            $request->iParentCarpetaId ?? null,
            $request->iCarpetaId ?? null,
            $request->cNombre ?? null,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::selectOne("EXEC repo.SP_UPD_carpeta $placeholders", $parametros);
    }

    public static function delCarpeta($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? null,
            $request->iCarpetaId ?? null,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::selectOne("EXEC repo.SP_DEL_carpeta $placeholders", $parametros);
    }
}
