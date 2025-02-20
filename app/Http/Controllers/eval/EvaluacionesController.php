<?php

namespace App\Http\Controllers\eval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class EvaluacionesController extends Controller
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    private function decodeValue($value)
    {
        if (is_null($value)) {
            return null;
        }
        return is_numeric($value) ? $value : ($this->hashids->decode($value)[0] ?? null);
    }

    public function validateRequest(Request $request)
    {
        $request->validate(
            ['opcion' => 'required'],
            ['opcion.required' => 'Hubo un problema al obtener la acción']
        );

        $fieldsToDecode = [
            'valorBusqueda',

            'iEvaluacionId',
            'iTipoEvalId',
            'iProgActId',
            'iInstrumentoId',
            'iEscalaCalifId',
            'iDocenteId'

        ];

        foreach ($fieldsToDecode as $field) {
            $request[$field] = $this->decodeValue($request->$field);
        }

        return [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iEvaluacionId                 ??  NULL,
            $request->iTipoEvalId                   ??  NULL,
            $request->iProgActId                    ??  NULL,
            $request->iInstrumentoId                ??  NULL,
            $request->iEscalaCalifId                ??  NULL,
            $request->iDocenteId                    ??  NULL,
            $request->dtEvaluacionPublicacion       ??  NULL,
            $request->cEvaluacionTitulo             ??  NULL,
            $request->cEvaluacionDescripcion        ??  NULL,
            $request->cEvaluacionObjetivo           ??  NULL,
            $request->nEvaluacionPuntaje            ??  NULL,
            $request->iEvaluacionNroPreguntas       ??  NULL,
            $request->dtEvaluacionInicio            ??  NULL,
            $request->dtEvaluacionFin               ??  NULL,
            $request->iEvaluacionDuracionHoras      ??  NULL,
            $request->iEvaluacionDuracionMinutos    ??  NULL,
            $request->iEstado                       ??  NULL,
            $request->iSesionId                     ??  NULL,
            $request->dtCreado                      ??  NULL,
            $request->dtActualizado                 ??  NULL,
            $request->iEvaluacionIdPadre            ??  NULL,
            $request->cEvaluacionArchivoAdjunto     ??  NULL,

            $request->iCredId                       ??  NULL
        ];
    }

    private function encodeFields($item)
    {
        $fieldsToEncode = [
            'iEvaluacionId',
            'iTipoEvalId',
            'iProgActId',
            'iInstrumentoId',
            'iEscalaCalifId',
            'iDocenteId'
        ];

        foreach ($fieldsToEncode as $field) {
            if (isset($item->$field)) {
                $item->$field = $this->hashids->encode($item->$field);
            }
        }

        return $item;
    }

    public function encodeId($data)
    {
        return array_map([$this, 'encodeFields'], $data);
    }

    public function handleCrudOperation(Request $request)
    {
        $parametros = $this->validateRequest($request);

        try {
            switch ($request->opcion) {
                case 'CONSULTARxiEvaluacionId':
                    $data = DB::select('exec eval.Sp_SEL_evaluaciones ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    $data = $this->encodeId($data);
                    return new JsonResponse(
                        ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data],
                        200
                    );
                    break;
                case 'GUARDARxProgActxiEvaluacionId':
                    $data = DB::select('exec eval.Sp_INS_evaluaciones ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iEvaluacionId > 0) {
                        $data = $this->encodeId($data);
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'Se guardó la información', 'data' => $data],
                            200
                        );
                    } else {
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'No se ha podido guardar la información', 'data' => null],
                            500
                        );
                    }
                case 'ELIMINAR':
                    $data = DB::select('exec eval.Sp_DEL_evaluaciones ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iEvaluacionId > 0) {
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'Se eliminó la información', 'data' => null],
                            200
                        );
                    } else {
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'No se ha podido eliminar la información', 'data' => null],
                            500
                        );
                    }
                case 'ACTUALIZARxProgActxiEvaluacionId':
                    $data = DB::select('exec eval.Sp_UPD_evaluaciones ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iEvaluacionId > 0) {
                        $data = $this->encodeId($data);
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'Se actualizó la información', 'data' => $data],
                            200
                        );
                    } else {
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'No se ha podido actualizar la información', 'data' => null],
                            500
                        );
                    }
            }
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => $e->getMessage(), 'data' => []],
                500
            );
        }
    }

    public function guardarConclusionxiEvalPromId(Request $request){
        $data = DB::update(
            "   UPDATE eval.evaluacion_promedios
                SET cConclusionDescriptiva = '" . $request->cConclusionDescriptiva . "'
                WHERE iEvalPromId = '" . $request->iEvalPromId . "'
            "
        );

        if ($data) {
            $response = ['validated' => true, 'mensaje' => 'Se actualizó la respuesta.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se pudo actualizar la respuesta.'];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }
}
