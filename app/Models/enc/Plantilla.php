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
            $request->iTipoReporte,
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
            $request->jsonPoblacion,
            $request->jsonAccesos,
            $request->bCompartirMismaIe,
            $request->bCompartirDirectores,
            $request->bCompartirEspUgel,
            $request->bCompartirEspDremo,
            $request->bCompartirMismaUgel,
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
            $request->jsonPoblacion,
            $request->jsonAccesos,
            $request->bCompartirMismaIe,
            $request->bCompartirDirectores,
            $request->bCompartirEspUgel,
            $request->bCompartirEspDremo,
            $request->bCompartirMismaUgel,
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

    public static function updPlantillaEstado($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPlanId,
            $request->iEstado,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_UPD_plantillaEstado $placeholders", $parametros);
    }

    public static function insPlantillaDuplicado($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iCateId,
            $request->iPlanId,
            $request->cPlanNombre,
            $request->cPlanSubtitulo,
            $request->bCopiarPoblacion,
            $request->bCopiarAccesos,
            $request->bCopiarPreguntas,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_INS_plantillaDuplicado $placeholders", $parametros);
    }

    public static function insPlantillaEncuesta($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iCateId,
            $request->iEncuId,
            $request->cPlanNombre,
            $request->cPlanSubtitulo,
            $request->bCopiarPoblacion,
            $request->bCopiarAccesos,
            $request->bCopiarPreguntas,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_INS_plantillaEncuesta $placeholders", $parametros);
    }

    public static function updPlantillaArchivar($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPlanId,
            $request->bArchivar,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_UPD_plantillaArchivar $placeholders", $parametros);
    }
}
