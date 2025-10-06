<?php

namespace App\Models\enc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PLantilla extends Model
{
    public static function selPlantillas($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iCateId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC enc.Sp_SEL_plantillas $placeholders", $parametros);
    }

    public static function selPlantilla($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPlanId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_SEL_plantilla $placeholders", $parametros);
    }

    public static function insPlantilla($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->cPlanNombre,
            $request->cPlanSubtitulo,
            $request->cPlanDescripcion,
            $request->iCateId,
            $request->iTiemDurId,
            $request->jsonPoblacion,
            $request->jsonAccesos,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_INS_plantilla $placeholders", $parametros);
    }

    public static function updPlantilla($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPlanId,
            $request->cPlanNombre,
            $request->cPlanSubtitulo,
            $request->cPlanDescripcion,
            $request->iCateId,
            $request->iTiemDurId,
            $request->jsonPoblacion,
            $request->jsonAccesos,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_UPD_plantilla $placeholders", $parametros);
    }

    public static function delPlantilla($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPlanId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_DEL_plantilla $placeholders", $parametros);
    }
}
