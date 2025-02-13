<?php

namespace App\Repositories\Ere;

use Illuminate\Support\Facades\DB;

class EvaluacionesRepository
{
    public static function obtenerEvaluacionPorId($iEvaluacionId)
    {
        $evaluacion = DB::selectOne(
            'SELECT * FROM ere.evaluacion AS e
             INNER JOIN ere.nivel_evaluaciones AS ne ON e.iNivelEvalId=ne.iNivelEvalId
             WHERE iEvaluacionId = ?',
            [$iEvaluacionId]
        );
        return $evaluacion;
    }
}
