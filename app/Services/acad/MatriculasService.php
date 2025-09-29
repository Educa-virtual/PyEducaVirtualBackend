<?php

namespace App\Services\acad;

use App\Helpers\VerifyHash;
use App\Models\acad\Matricula;

class MatriculasService
{
    public static function obtenerDetallesMatriculaEstudiantePorCredPerfId($iCredEntPerfId, $iYAcadId)
    {
        return Matricula::selDetalleMatriculaEstudiantePorCredPerfId($iCredEntPerfId, $iYAcadId);
    }

    public static function obtenerDetalleMatriculaEstudiantePorId($iMatrId)
    {
        return Matricula::selDetalleMatriculaEstudiantePorId($iMatrId);
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
