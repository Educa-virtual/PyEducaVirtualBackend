<?php

namespace App\Models\acad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Desercion extends Model
{
    public static function selDeserciones(Object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYacadId,
            $request->iMatrId,
            $request->iEstudianteId,
            $request->iTipoDesercionId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.SP_SEL_deserciones $placeholders", $parametros);
    }

    public static function insDesercion(Object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iMatrId,
            $request->iTipoDesercionId,
            $request->dInicioDesercion,
            $request->dFinDesercion,
            $request->cMotivoDesercion,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.SP_INS_desercion $placeholders", $parametros);
    }

    public static function updDesercion(Object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iDesercionId,
            $request->iTipoDesercionId,
            $request->dInicioDesercion,
            $request->dFinDesercion,
            $request->cMotivoDesercion,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.SP_UPD_desercion $placeholders", $parametros);
    }

    public static function delDesercion(Object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iDesercionId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.SP_DEL_desercion $placeholders", $parametros);
    }

    public static function selDesercion(Object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iDesercionId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.SP_SEL_desercion $placeholders", $parametros);
    }

}
