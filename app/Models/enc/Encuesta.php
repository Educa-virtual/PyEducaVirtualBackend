<?php

namespace App\Models\enc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Encuesta extends Model
{
    public static function selEncuestas($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->iCateId,
            $request->iTipoUsuario,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC enc.Sp_SEL_encuestas $placeholders", $parametros);
    }

    public static function selEncuestaParametros($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_SEL_encuestaParametros $placeholders", $parametros);
    }

    public static function selEncuesta($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEncuId,
            $request->iTipoUsuario,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_SEL_encuesta $placeholders", $parametros);
    }

    public static function insEncuesta($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->cEncuNombre,
            $request->cEncuSubtitulo,
            $request->cEncuDescripcion,
            $request->dEncuInicio,
            $request->dEncuFin,
            $request->iCateId,
            $request->iTiemDurId,
            $request->jsonPoblacion,
            $request->jsonAccesos,
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_INS_encuesta $placeholders", $parametros);
    }

    public static function updEncuesta($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEncuId,
            $request->cEncuNombre,
            $request->cEncuSubtitulo,
            $request->cEncuDescripcion,
            $request->dEncuInicio,
            $request->dEncuFin,
            $request->iCateId,
            $request->iTiemDurId,
            $request->jsonPoblacion,
            $request->jsonAccesos,
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_UPD_encuesta $placeholders", $parametros);
    }

    public static function delEncuesta($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEncuId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_DEL_encuesta $placeholders", $parametros);
    }

    public static function selPoblacionObjetivo($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->jsonPoblacion,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_SEL_encuestaPoblacion $placeholders", $parametros);
    }

    public static function updEncuestaEstado($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEncuId,
            $request->iEstado,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_UPD_encuestaEstado $placeholders", $parametros);
    }
}
