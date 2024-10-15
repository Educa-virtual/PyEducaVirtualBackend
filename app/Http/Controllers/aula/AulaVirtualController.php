<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\ApiController;
use DateTime;
use DateTimeZone;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

use function PHPUnit\Framework\isNull;

class AulaVirtualController extends ApiController
{

    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

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
            $request->bTareaEsEvaluado,
            0,
            0,
            $isoDateUTC,
            $fechaHoraCompleta,
            null,
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

    public function contenidoSemanasProgramacionActividades(Request $request)
    {
        $iSilaboId = $request->iSilaboId;
        $iSilaboId = $this->hashids->decode($iSilaboId);
        $iSilaboId = count($iSilaboId) > 0 ? $iSilaboId[0] : $iSilaboId;

        $params = [$iSilaboId];

        $contenidos = [];
        try {
            $contenidos = DB::select('exec aula.Sp_SEL_contenido_semana_programacion_actividades @_iSilaboId = ?', $params);
        } catch (Throwable $e) {
            $message = $this->handleAndLogError($e, 'Error al obtener los datos');
            return $this->errorResponse(null, $message);
        }

        $result = [];

        foreach ($contenidos as $row) {
            $iContenidoSemId = $row->iContenidoSemId;
            $dtProgActPublicacion = $row->dtProgActPublicacion;
            $actividades = $row->actividadesJSON;

            if (!isset($result[$iContenidoSemId])) {
                $result[$iContenidoSemId] = [
                    'cContenidoSemTitulo' => $row->cContenidoSemTitulo,
                    'cContenidoSemNumero' => $row->cContenidoSemNumero,
                    'iContenidoSemId' => $row->iContenidoSemId,
                    'fechas' => []
                ];
            }

            if (!isset($result[$iContenidoSemId]['fechas'][$dtProgActPublicacion]) && !is_null($dtProgActPublicacion)) {
                $contenido = $actividades ? json_decode($actividades, true) : [];
                $result[$iContenidoSemId]['fechas'][$dtProgActPublicacion] =  [
                    'fecha' => $dtProgActPublicacion,
                    'actividades' => $contenido
                ];
            }
        }

        $finalResult =  array_values($result);
        $finalResult = array_map(function ($item) {
            $item['fechas'] = array_values($item['fechas']);
            return $item;
        }, $finalResult);

        return $this->successResponse($finalResult, 'Datos obtenidos correctamente');
    }
}
