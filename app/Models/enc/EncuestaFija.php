<?php

namespace App\Models\enc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EncuestaFija extends Model
{
    public static function insEncuestaAutoevaluacion($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->iCateId,
            $request->iPlanId,
            $request->iEncuId,
            $request->jsonPeriodosCursos,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::insert("EXEC enc.Sp_INS_encuestaAutoevaluacion $placeholders", $parametros);
    }

    public static function insEncuestaSatisfaccion($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->iCateId,
            $request->iPlanId,
            $request->iEncuId,
            $request->jsonCursos,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::insert("EXEC enc.Sp_INS_encuestaSatisfaccion $placeholders", $parametros);
    }

    public static function selEncuestaParametrosFija($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->iCateId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC enc.Sp_SEL_encuestaParametrosFija $placeholders", $parametros);
    }
}
