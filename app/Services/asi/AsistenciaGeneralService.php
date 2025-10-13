<?php

namespace App\Services\asi;

use App\Jobs\NotificarApoderadosInasistenciaGeneralJob;
use App\Models\asi\AsistenciaGeneral;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AsistenciaGeneralService
{
    public static function obtenerCantidadRegistrosPorTipo($iEstudianteId, $iYAcadId, $iSedeId, $iTipoAsiId, $desde, $hasta)
    {
        return AsistenciaGeneral::selCantidadRegistrosPorTipo($iEstudianteId, $iYAcadId, $iSedeId, $iTipoAsiId, $desde, $hasta);
    }

    public static function notificarApoderadosInasistenciaGeneral(Request $request)
    {
        $asistencias = json_decode($request->asistencia, true);
        $matriculasInasistencias = [];
        foreach ($asistencias as $asistencia) {
            if ($asistencia['iTipoAsiId'] == 3) {
                array_push($matriculasInasistencias, $asistencia['iMatrId']);
            }
        }
        if (count($matriculasInasistencias) > 0) {
            $fecha = Carbon::parse($request->dtAsistencia);
            $data = AsistenciaGeneral::selEstudiantesConFalta($fecha->format('Y-m-d'), $matriculasInasistencias);
            NotificarApoderadosInasistenciaGeneralJob::dispatch($data, $fecha->format('d/m/Y'));
        }
    }

    public static function marcarAsistenciaGeneralNotificada($iAsistenciaId)
    {
        $asistencia = AsistenciaGeneral::find($iAsistenciaId);
        if ($asistencia) {
            $asistencia->bNotificado = 1;
            $asistencia->save();
        }
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
