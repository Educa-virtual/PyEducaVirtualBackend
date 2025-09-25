<?php

namespace App\Services\ere;

use App\Models\ere\Evaluacion;

class EvaluacionesService
{
    public static function obtenerEvaluacionesEstudiantePorAnio($iEstudianteId, $anio)
    {
        return Evaluacion::selEvaluacionesEstudiantePorAnio($iEstudianteId, $anio);
    }

    public static function obtenerResultadosEstudiantePorEvaluacion($anio, $iEstudianteId)
    {
        $resultado = Evaluacion::selResultadoEvaluacionEstudiante($iEstudianteId, $anio);
        foreach ($resultado as $res) {
            if ($res->iEnBlanco == null) {
                $res->iEnBlanco = intval($res->iCantidadPreguntas) - (intval($res->iCantidadCorrectas) + intval($res->iCantidadIncorrectas));
            }
        }
        return $resultado;
    }
}
