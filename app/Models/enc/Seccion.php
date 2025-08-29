<?php

namespace App\Models\enc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Seccion extends Model
{
    public static function selSecciones($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEncuId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC enc.Sp_SEL_secciones $placeholders", $parametros);
    }

    public static function selSeccion($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iSeccionId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_SEL_seccion $placeholders", $parametros);
    }

    public static function insSeccion($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEncuId,
            $request->iSeccionOrden,
            $request->cSeccionTitulo,
            $request->cSeccionDescripcion,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_INS_seccion $placeholders", $parametros);
    }

    public static function updSeccion($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iSeccionId,
            $request->iEncuId,
            $request->iSeccionOrden,
            $request->cSeccionTitulo,
            $request->cSeccionDescripcion,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_UPD_seccion $placeholders", $parametros);
    }

    public static function delSeccion($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iSeccionId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_DEL_seccion $placeholders", $parametros);
    }
}
