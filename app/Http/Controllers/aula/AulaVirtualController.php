<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class AulaVirtualController extends ApiController
{

    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function guardarActividad(Request $request)
    {
        $params = [
            2,
            $request->iDocenteId,
            $request->cTareaTitulo,
            $request->cTareaDescripcion,
            '',
            $request->cTareaIndicaciones,
            0,
            0,
            0,
            null,
            null,
            '',
            1,
            null
        ];

        try {
            $resp = DB::select('EXEC [aula].[SP_INS_InsertActividades]
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

            if (!isset($result[$iContenidoSemId]['fechas'][$dtProgActPublicacion])) {
                $result[$iContenidoSemId]['fechas'][$dtProgActPublicacion] = json_decode($actividades, true);
            }
        }

        $finalResult =  array_values($result);

        return $this->successResponse($finalResult, 'Datos obtenidos correctamente');
    }
}
