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
            $request->iCatEncId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC enc.Sp_SEL_encuestas $placeholders", $parametros);
    }

    public static function selEncuestaParametros($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_SEL_encuestaParametros $placeholders", $parametros);
    }

    public static function selEncuesta($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->iEncId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_SEL_encuesta $placeholders", $parametros);
    }

    public static function insEncuesta($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->cEncNombre,
            $request->iYAcadId,
            $request->iCatEncId,
            $request->cEncSubtitulo,
            $request->dEncInicio,
            $request->dEncFin,
            $request->iDurId,
            $request->cEncDescripcion,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_INS_encuesta $placeholders", $parametros);
    }

    public static function updEncuesta($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEncId,
            $request->cEncNombre,
            $request->iYAcadId,
            $request->iCatEncId,
            $request->cEncSubtitulo,
            $request->dEncInicio,
            $request->dEncFin,
            $request->iDurId,
            $request->cEncDescripcion,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC enc.Sp_UPD_encuesta $placeholders", $parametros);
    }

    public static function delEncuesta($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEncId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::delete("EXEC enc.Sp_DEL_encuesta $placeholders", $parametros);
    }
}
