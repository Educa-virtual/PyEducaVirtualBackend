<?php

namespace App\Models\enc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PlantillaPregunta extends Model
{
    public static function selPlantillaPreguntas($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEncuId,
            $request->iPreguntaId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC enc.Sp_SEL_plantillaPreguntas $placeholders", $parametros);
    }

    public static function selPlantillaPregunta($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPregId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_SEL_plantillaPregunta $placeholders", $parametros);
    }

    public static function insPlantillaPregunta($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iSeccionId,
            $request->iTipoPregId,
            $request->iPregOrden,
            $request->cPregContenido,
            $request->cPregAdicional,
            $request->jsonAlternativas,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_INS_plantillaPregunta $placeholders", $parametros);
    }

    public static function updPlantillaPregunta($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPregId,
            $request->iSeccionId,
            $request->iTipoPregId,
            $request->iPregOrden,
            $request->cPregContenido,
            $request->cPregAdicional,
            $request->jsonAlternativas,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_UPD_plantillaPregunta $placeholders", $parametros);
    }

    public static function delPlantillaPregunta($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPregId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_DEL_plantillaPregunta $placeholders", $parametros);
    }
}
