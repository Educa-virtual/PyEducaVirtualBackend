<?php

namespace App\Models\enc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pregunta extends Model
{
    public static function selPreguntas($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEncuId,
            $request->iPreguntaId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC enc.Sp_SEL_preguntas $placeholders", $parametros);
    }

    public static function selPregunta($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPregId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_SEL_pregunta $placeholders", $parametros);
    }

    public static function insPregunta($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iSeccionId,
            $request->iTipoPregId,
            $request->cPregOrden,
            $request->cPregContenido,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_INS_pregunta $placeholders", $parametros);
    }

    public static function updPregunta($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPregId,
            $request->iSeccionId,
            $request->iTipoPregId,
            $request->cPregOrden,
            $request->cPregContenido,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_UPD_pregunta $placeholders", $parametros);
    }

    public static function delPregunta($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPregId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_DEL_pregunta $placeholders", $parametros);
    }
}
