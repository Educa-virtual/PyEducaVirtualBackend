<?php

namespace App\Services\asi;

use App\Models\asi\AsistenciaGeneral;
use Carbon\Carbon;

class AsistenciaGeneralService
{
    public static function obtenerCantidadRegistrosPorTipo($iEstudianteId, $iYAcadId, $iSedeId, $iTipoAsiId, $desde, $hasta)
    {
        return AsistenciaGeneral::selCantidadRegistrosPorTipo($iEstudianteId, $iYAcadId, $iSedeId, $iTipoAsiId, $desde, $hasta);
    }

    public static function obtenerAsistenciaEstudiantePorPeriodo($matricula, $anio, $mes)
    {
        $data = AsistenciaGeneral::selAsistenciaEstudiantePorPeriodo($matricula->iMatrId, $anio, $mes);
        foreach ($data as $fila) {
            $fechaCarbon=Carbon::parse($fila->dtAsistencia);
            $fila->cursos = AsistenciaControlService::obtenerAsistenciaEstudiantePorFecha($matricula, $fechaCarbon->format('Ymd'));
        }
        return $data;
    }
}
