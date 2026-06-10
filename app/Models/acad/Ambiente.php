<?php

namespace App\Models\acad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ambiente extends Model
{
    public static function selAmbientes($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iConfigId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.Sp_SEL_ambientes $placeholders", $parametros);
    }

    public static function selAmbiente($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iConfigId,
            $request->iIieeAmbienteId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_SEL_ambiente $placeholders", $parametros);
    }

    public static function insAmbiente($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iConfigId,
            $request->iTipoAmbienteId,
            $request->iEstadoAmbId,
            $request->iUbicaAmbId,
            $request->iUsoAmbId,
            $request->iPisoAmbid,
            $request->bAmbienteEstado,
            $request->cAmbienteNombre,
            $request->cAmbienteDescripcion,
            $request->iAmbienteArea,
            $request->iAmbienteAforo,
            $request->cAmbienteObs,
            $request->cImagen,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::insert("EXEC acad.Sp_INS_ambiente $placeholders", $parametros);
    }

    public static function updAmbiente($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iIieeAmbienteId,
            $request->iTipoAmbienteId,
            $request->iEstadoAmbId,
            $request->iUbicaAmbId,
            $request->iUsoAmbId,
            $request->iPisoAmbid,
            $request->bAmbienteEstado,
            $request->cAmbienteNombre,
            $request->cAmbienteDescripcion,
            $request->iAmbienteArea,
            $request->iAmbienteAforo,
            $request->cAmbienteObs,
            $request->cImagen,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC acad.Sp_UPD_ambiente $placeholders", $parametros);
    }

    public static function delAmbiente($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iIieeAmbienteId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::delete("EXEC acad.Sp_DEL_ambiente $placeholders", $parametros);
    }
}