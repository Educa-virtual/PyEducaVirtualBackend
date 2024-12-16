<?php

namespace App\Http\Controllers\eval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class EvaluacionPreguntasController extends Controller
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
            'iEvalPregId',
            'iEvaluacionId',
            'iBancoId'
        ];

        foreach ($fieldsToDecode as $field) {
            $request[$field] = $this->decodeValue($request->$field);
        }

        return [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iEvalPregId           ??  NULL,
            $request->iEvaluacionId         ??  NULL,
            $request->iBancoId              ??  NULL,
            $request->cEvalPregPregunta     ??  NULL,
            $request->dtEvalPregTiempo      ??  NULL,
            $request->cEvalPregTextoAyuda   ??  NULL,
            $request->nEvalPregPuntaje      ??  NULL,
            $request->iEstado               ??  NULL,
            $request->iSesionId             ??  NULL,
            $request->dtCreado              ??  NULL,
            $request->dtActualizado         ??  NULL,

            $request->iCredId               ??  NULL
        ];
    }

    private function encodeFields($item)
    {
        $fieldsToEncode = [
            'idEncabPregId',
            'iEvalPregId',
            'iEvaluacionId',
            'iBancoId'
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
                case 'CONSULTAR':
                    $data = DB::select('exec eval.Sp_SEL_evaluacionPreguntasxiCredId ?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    $data = $this->encodeId($data);
                    return new JsonResponse(
                        ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data],
                        200
                    );
                    break;
                case 'GUARDARxBancoPreguntas':
                    $data = DB::select('exec eval.Sp_INS_evaluacionPreguntasxiCredId ?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iEvalPregId > 0) {
                        $resp = new BancoAlternativasController();
                        return $resp->handleCrudOperation($request);
                    } else {
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'No se ha podido guardar la información', 'data' => null],
                            500
                        );
                    }
                case 'ELIMINAR':
                    $data = DB::select('exec eval.Sp_DEL_evaluacionPreguntasxiCredId ?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iEvalPregId > 0) {
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
                case 'ACTUALIZAR':
                    $data = DB::select('exec eval.Sp_UPD_evaluacionPreguntasxiCredId ?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iEvalPregId > 0) {
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'Se actualizó la información', 'data' => null],
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
}
