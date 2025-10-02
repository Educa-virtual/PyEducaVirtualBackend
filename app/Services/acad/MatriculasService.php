<?php

namespace App\Services\acad;

use App\Helpers\VerifyHash;
use App\Models\acad\Matricula;
use Exception;

class MatriculasService
{
    public static function obtenerDetalleMatriculaEstudiante($params)
    {
        $data = Matricula::selDetalleMatriculaEstudiante($params);
        if (!$data) {
            throw new Exception("No existe una matrícula para el año seleccionado o los parámetros enviados");
        }
        return $data;
    }

    public static function obtenerMatriculaPorId($request)
    {
        return Matricula::selMatriculaPorId($request);
    }

    public static function obtenerCursosMatricula($iMatrId)
    {
        return Matricula::selCursosMatricula($iMatrId);
    }

    public static function obtenerMatriculasEstudiante($request, $hashIds = true)
    {
        $data = Matricula::selMatriculas($request);
        if ($hashIds) {
            foreach ($data as $fila) {
                $fila->iEstudianteId = VerifyHash::encodexId($fila->iEstudianteId);
                $fila->iMatrId = VerifyHash::encodexId($fila->iMatrId);
            }
        }
        return $data;
    }
}
