<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\ApiController;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AulaVirtualController extends ApiController
{
    public function guardarActividad(Request $request)
    {
        $date = date('Y-m-d H:i:s');
        $dateTime = new DateTime($date);
        $isoDate = $dateTime->format(DateTime::ATOM);
        $isoDateUTC = $dateTime->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d\TH:i:s.v\Z');

        // Extraer fecha y hora desde el request
        $fechaFin = $request->input('dFechaEvaluacionPublicacion');
        $horaFin = $request->input('tHoraEvaluacionPublicacion');
        $date2 = new DateTime($fechaFin);
        $dateString = $date2->format('Y-m-d');
        $hora = new DateTime($horaFin);
        $horaString = $hora->format('H:i:s');
        $fechaHoraCompleta = $dateString . 'T' . $horaString . 'Z';


        $params = [
            2,
            $request->iDocenteId,
            $request->cTareaTitulo,
            $request->cTareaDescripcion,
            $request->cTareaArchivoAdjunto,
            $request->cTareaIndicaciones,
            0,
            0,
            0,
            $isoDateUTC,
            $fechaHoraCompleta,
            '',
            1,
            null
        ];

        try {
            $resp = DB::statement('EXEC [aula].[SP_INS_InsertActividades]
                    @iProgActId  = ? ,
                    @iDocenteId = ? ,
                    @cTareaTitulo = ?,
                    @cTareaDescripcion = ?,
                    @cTareaArchivoAdjunto = ?,
                    @cTareaIndicaciones = ?,
                    @bTareaEsEvaluado = ?,
                    @bTareaEsRestringida = ?,
                    @bTareaEsGrupal = ?,
                    @dtTareaInicio = ?,
                    @dtTareaFin = ?,
                    @cTareaComentarioDocente = ?,
                    @iEstado = ?,
                    @iSesionId = ?
            ', $params);
            return $this->successResponse($resp, 'Datos guardados correctamente');
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al guardar la actividad');
        }
    }
}
