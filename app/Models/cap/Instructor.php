<?php

namespace App\Models\cap;

use App\Helpers\VerifyHash;
use Illuminate\Support\Facades\DB;

class Instructor
{
    public static function selInstructores($request)
    {
        $parametros = [
            $request->iEstado,
            $request->header('iCredId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC cap.SP_SEL_instructores $placeholders", $parametros);
    }

    public static function insInstructores($request)
    {
        $parametros = [
            $request->iPersId,
            $request->header('iCredId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC cap.SP_INS_instructores $placeholders", $parametros);
    }

    public static function delInstructores($request)
    {
        $parametros = [
            $request->iInstId,
            $request->header('iCredId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC cap.SP_DEL_instructores $placeholders", $parametros);
    }

    public static function updInstructores($request)
    {
        $parametros = [
            $request->cOpcion,
            $request->iInstId,
            $request->cPersDireccion,
            $request->cPersCorreo,
            $request->cPersCelular,
            $request->header('iCredId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC cap.SP_UPD_instructores $placeholders", $parametros);
    }

    public static function updInstructoresEstado($request)
    {
        $parametros = [
            $request->header('iCredId'),
            $request->iInstId,
            $request->iEstado,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC cap.SP_UPD_instructoresEstado $placeholders", $parametros);
    }
}
