<?php

namespace App\Models\ere;

use Hashids\Hashids;
use Illuminate\Support\Facades\DB;

class NivelLogro
{
    public static function selNivelLogroEvalCurso($request)
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $iEvaluacionId = $request->iEvaluacionId ?? null;
        $iCursosNivelGradId = $request->iCursosNivelGradId ?? null;

        $parametros = [
            $request->header('iCredEntPerfId'),
            is_numeric($iEvaluacionId) ? $iEvaluacionId : ($hashids->decode($iEvaluacionId)[0] ?? null),
            is_numeric($iCursosNivelGradId) ? $iCursosNivelGradId : ($hashids->decode($iCursosNivelGradId)[0] ?? null),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::select("EXEC ere.Sp_SEL_nivelLogroEvalCurso $placeholders", $parametros);
    }
}
