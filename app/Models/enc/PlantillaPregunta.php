<?php

namespace App\Models\enc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PlantillaPregunta extends Model
{
    public static function selPlantillaPregunta($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPlanPregId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_SEL_plantillaPregunta $placeholders", $parametros);
    }

    public static function insPlantillaPregunta($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPlanSeccionId,
            $request->iTipoPregId,
            $request->iPlanPregOrden,
            $request->cPlanPregContenido,
            $request->cPlanPregAdicional,
            $request->jsonAlternativas,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_INS_plantillaPregunta $placeholders", $parametros);
    }

    public static function updPlantillaPregunta($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPlanPregId,
            $request->iPlanSeccionId,
            $request->iTipoPregId,
            $request->iPlanPregOrden,
            $request->cPlanPregContenido,
            $request->cPlanPregAdicional,
            $request->jsonAlternativas,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_UPD_plantillaPregunta $placeholders", $parametros);
    }

    public static function delPlantillaPregunta($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPlanPregId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_DEL_plantillaPregunta $placeholders", $parametros);
    }
}
