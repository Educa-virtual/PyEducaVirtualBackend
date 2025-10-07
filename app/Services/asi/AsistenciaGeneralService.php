<?php

namespace App\Services\asi;

use App\Jobs\NotificarApoderadosAsistenciaJob;
use App\Models\asi\AsistenciaGeneral;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AsistenciaGeneralService
{
    public static function obtenerCantidadRegistrosPorTipo($iEstudianteId, $iYAcadId, $iSedeId, $iTipoAsiId, $desde, $hasta)
    {
        return AsistenciaGeneral::selCantidadRegistrosPorTipo($iEstudianteId, $iYAcadId, $iSedeId, $iTipoAsiId, $desde, $hasta);
    }

    public static function notificarApoderadosInasistencia(Request $request)
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
            $data = AsistenciaGeneral::selEstudiantesConFalta($fecha->format('Ymd'), $matriculasInasistencias);
            NotificarApoderadosAsistenciaJob::dispatch($data, $fecha->format('d/m/Y'));
        }
    }

    public static function marcarNotificado($iAsistenciaId)
    {
        $asistencia = AsistenciaGeneral::find($iAsistenciaId);
        if ($asistencia) {
            $asistencia->bNotificado = 1;
            $asistencia->save();
        }
    }
}
