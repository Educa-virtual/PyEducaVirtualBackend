<?php

namespace App\Services\apo;

use App\Helpers\VerifyHash;
use App\Models\apo\Apoderado;
use Exception;

class ApoderadosService
{
    public static function obtenerEstudiantesPorApoderado($iPersId)
    {
        $data = Apoderado::selEstudiantesPorApoderado($iPersId);
        foreach ($data as $fila) {
            $fila->iEstudianteId=VerifyHash::encodexId($fila->iEstudianteId);
        }
        return $data;
    }

    public static function estudiantePerteneceApoderado($iPersIdApoderado, $iEstudianteId) {
        $data = Apoderado::selEstudianteApoderado($iPersIdApoderado, $iEstudianteId);
        if (!$data) {
            throw new Exception("El estudiante no esta relacionado con el apoderado");
        }
    }
}
