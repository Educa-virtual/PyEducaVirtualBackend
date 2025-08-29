<?php

namespace App\Services\ere;

use App\Models\ere\Evaluacion;

class EvaluacionesService
{
    public static function obtenerEvaluacionesPorEstudiante($iEstudianteId)
    {
        return Evaluacion::selEvaluacionesPorEstudiante($iEstudianteId);
    }

    public static function obtenerResultadoEvaluacionEstudiante($iEvaluacionId, $iCursoId, $iEstudianteId)
    {
        $resultado = Evaluacion::selResultadoEvaluacionEstudiante($iEvaluacionId, $iCursoId, $iEstudianteId);
        if ($resultado && $resultado->iEnBlanco==null) {
            $resultado->iEnBlanco = intval($resultado->iCantidadPreguntas) - (intval($resultado->iCantidadCorrectas) + intval($resultado->iCantidadIncorrectas));
        }
        return $resultado;
    }
}
