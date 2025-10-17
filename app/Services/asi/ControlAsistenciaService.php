<?php

namespace App\Services\asi;

use App\Jobs\NotificarApoderadosInasistenciaCursoJob;
use App\Models\asi\ControlAsistencia;
use App\Services\acad\DocentesService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ControlAsistenciaService
{
    public static function notificarApoderadosInasistenciaCurso($solicitud)
    {
        $fechaCarbon = Carbon::parse($solicitud[2]);
        $data = ControlAsistencia::selEstudiantesConFalta($solicitud, $fechaCarbon);
        $docente=DocentesService::obtenerDocentePorId($solicitud[7]);
        NotificarApoderadosInasistenciaCursoJob::dispatch($data, $fechaCarbon->format('d/m/Y'), $docente);
    }

    public static function marcarAsistenciaNotificada($iAsistenciaId)
    {
        $asistencia = ControlAsistencia::find($iAsistenciaId);
        if ($asistencia) {
            $asistencia->bNotificado = 1;
            $asistencia->save();
        }
    }
}
