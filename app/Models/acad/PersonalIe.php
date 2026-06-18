<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class PersonalIe
{
    public static function selPersonalIes($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iConfigId,
            $request->iSedeId,
            $request->iYAcadId,
            $request->iPersCargoId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.Sp_SEL_personal_ies $placeholders", $parametros);
    }

    public static function insPersonalIe($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPersId,
            $request->iConfigId,
            $request->iYAcadId,
            $request->iPersCargoId,
            $request->iSedeId,
            $request->iHorasLabora,
            $request->cTipoTrabajador,
            $request->iHorasDictado,
            $request->cMotivo,
            $request->dtPersIeInicio,
            $request->dtPersIeFin,
            $request->cCodigoPlaza,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC acad.Sp_INS_personal_ie $placeholders", $parametros);
    }

    public static function updPersonalIe($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPersIeId,
            $request->iPersCargoId,
            $request->iHorasLabora,
            $request->cTipoTrabajador,
            $request->iHorasDictado,
            $request->cMotivo,
            $request->dtPersIeInicio,
            $request->dtPersIeFin,
            $request->cCodigoPlaza,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC acad.Sp_UPD_personal_ie $placeholders", $parametros);
    }

    public static function updPersonalIeEstado($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPersIeId,
            $request->bActivo,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC acad.Sp_UPD_personal_ieEstado $placeholders", $parametros);
    }

    public static function delPersonalIe($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPersIeId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC acad.Sp_DEL_personal_ie $placeholders", $parametros);
    }
}