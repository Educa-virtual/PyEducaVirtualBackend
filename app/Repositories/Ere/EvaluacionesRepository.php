<?php

namespace App\Repositories\ere;

use Illuminate\Support\Facades\DB;

class EvaluacionesRepository
{
    public static function obtenerEvaluacionPorId($iEvaluacionId)
    {
        $evaluacion = DB::selectOne(
            'EXEC ere.SP_SEL_EvaluacionNivel @_iEvaluacion = ?',
            [$iEvaluacionId]
        );
        return $evaluacion;
    }
}
