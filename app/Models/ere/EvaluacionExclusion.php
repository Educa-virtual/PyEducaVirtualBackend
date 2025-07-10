<?php

namespace App\Models\ere;

use Hashids\Hashids;
use Illuminate\Support\Facades\DB;

class EvaluacionExclusion
{
    public static function selEvaluacionExclusiones($request)
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $id_cifrado = $request->iEvaluacionId ?? null;

        $parametros = [
            $request->iCredEntPerfId,
            is_numeric($id_cifrado) ? $id_cifrado : ($hashids->decode($id_cifrado)[0] ?? null),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("exec ere.Sp_SEL_evaluacionExclusiones $placeholders", $parametros);
    }

    public static function insEvaluacionExclusion($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEvaluacionId,
            $request->iMatrId,
            $request->cEvalExcluMotivo,
            $request->cEvalExcluArchivo,

        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::insert("exec ere.Sp_INS_evaluacionExclusion $placeholders", $parametros);
    }

    public static function updEvaluacionExclusion($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEvalExcluId,
            $request->iEvaluacionId,
            $request->iMatrId,
            $request->cEvalExcluMotivo,
            $request->cEvalExcluArchivo,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("exec ere.Sp_UPD_evaluacionExclusion $placeholders", $parametros);
    }

    public static function selEvaluacionExclusion($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEvalExcluId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("exec ere.Sp_SEL_evaluacionExclusion $placeholders", $parametros);
    }

    public static function delEvaluacionExclusion($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEvalExcluId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("exec ere.Sp_DEL_evaluacionExclusion $placeholders", $parametros);
    }
}