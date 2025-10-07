<?php

namespace App\Models\enc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PlantillaSeccion extends Model
{
    public static function selPlantillaSecciones($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPlanId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC enc.Sp_SEL_plantillaSecciones $placeholders", $parametros);
    }

    public static function selPlantillaSeccion($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPlanSeccionId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_SEL_plantillaSeccion $placeholders", $parametros);
    }

    public static function insPlantillaSeccion($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPlanId,
            $request->iPlanSeccionOrden,
            $request->cPlanSeccionTitulo,
            $request->cPlanSeccionDescripcion,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_INS_plantillaSeccion $placeholders", $parametros);
    }

    public static function updPlantillaSeccion($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPlanSeccionId,
            $request->iPlanSeccionOrden,
            $request->cPlanSeccionTitulo,
            $request->cPlanSeccionDescripcion,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_UPD_plantillaSeccion $placeholders", $parametros);
    }

    public static function delPlantillaSeccion($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPlanSeccionId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_DEL_plantillaSeccion $placeholders", $parametros);
    }
}
