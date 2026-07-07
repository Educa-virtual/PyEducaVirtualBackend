<?php

namespace App\Models\acad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GradoSeccion extends Model
{
    public static function selGradosSecciones($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iConfigId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::select("EXEC acad.Sp_SEL_gradosSecciones $placeholders", $parametros);
    }

    public static function selGradoSeccion($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iDetConfigId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::selectOne("EXEC acad.Sp_SEL_gradoSeccion $placeholders", $parametros);
    }

    public static function insGradoSeccion($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iConfigId,
            $request->iTurnoId,
            $request->iModalServId,
            $request->iIieeAmbienteId,
            $request->iPersIeIdTutor,
            $request->cDetConfNombreSeccion,
            $request->iDetConfCantEstudiantes,
            $request->cDetConfObs,
            $request->iSeccionId,
            $request->iNivelGradoId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::insert("EXEC acad.Sp_INS_gradoSeccion $placeholders", $parametros);
    }

    public static function updGradoSeccion($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iDetConfId,
            $request->iTurnoId,
            $request->iModalServId,
            $request->iIieeAmbienteId,
            $request->iPersIeIdTutor,
            $request->cDetConfNombreSeccion,
            $request->iDetConfCantEstudiantes,
            $request->cDetConfObs,
            $request->iSeccionId,
            $request->iNivelGradoId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::update("EXEC acad.Sp_UPD_gradoSeccion $placeholders", $parametros);
    }

    public static function delGradoSeccion($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iDetConfigId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::delete("EXEC acad.Sp_DEL_gradoSeccion $placeholders", $parametros);
    }
}
