<?php

namespace App\Models\repo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Helpers\VerifyHash;

class Carpeta extends Model
{
    public static function selCarpetas($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iCarpetaId ?? NULL,
            $request->iPersId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC repo.SP_SEL_carpetas $placeholders", $parametros);
    }

    public static function selCarpeta($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iCarpetaId ?? NULL,
            $request->iPersId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC repo.SP_SEL_carpeta $placeholders", $parametros);
    }

    public static function insCarpeta($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->cNombre ?? NULL,
            $request->iPersId ?? NULL,
            $request->iParentCarpetaId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC repo.SP_INS_carpeta $placeholders", $parametros);
    }

    public static function updCarpeta($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iCarpetaId ?? NULL,
            $request->cNombre ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC repo.SP_UPD_carpeta $placeholders", $parametros);
    }
}