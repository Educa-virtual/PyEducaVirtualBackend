<?php

namespace App\Http\Controllers\eval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class BancoPreguntasController extends Controller
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
            'iBancoId',
            'iDocenteId',
            'iTipoPregId',
            'iCurrContId',
            'iCursoId',
            'iNivelCicloId'
        ];

        foreach ($fieldsToDecode as $field) {
            $request[$field] = $this->decodeValue($request->$field);
        }

        return [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iBancoId          ??  NULL,
            $request->iDocenteId        ??  NULL,
            $request->iTipoPregId       ??  NULL,
            $request->iCurrContId       ??  NULL,
            $request->dtBancoCreacion   ??  NULL,
            $request->cBancoPregunta    ??  NULL,
            $request->dtBancoTiempo     ??  NULL,
            $request->cBancoTextoAyuda  ??  NULL,
            $request->nBancoPuntaje     ??  NULL,
            $request->iEstado           ??  NULL,
            $request->iSesionId         ??  NULL,
            $request->dtCreado          ??  NULL,
            $request->dtActualizado     ??  NULL,
            $request->idEncabPregId     ??  NULL,
            $request->iCursoId          ??  NULL,
            $request->iNivelCicloId     ??  NULL,

            $request->iCredId           ??  NULL
        ];
    }

    private function encodeFields($item)
    {
        $fieldsToEncode = [
            'iBancoId',
            'iDocenteId',
            'iTipoPregId',
            'iCurrContId',
            'iCursoId',
            'iNivelCicloId'
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
                case 'CONSULTARxiBancoId':
                    $data = DB::select('exec eval.Sp_SEL_bancoPreguntasxiCredId ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    //$data = $this->encodeId($data);
                    return new JsonResponse(
                        ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data],
                        200
                    );
                    break;
                case 'GUARDARxBancoPreguntas':
                    $data = DB::select('exec eval.Sp_INS_bancoPreguntasxiCredId ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iBancoId > 0) {
                        $request['iBancoId'] = $this->hashids->encode($data[0]->iBancoId);
                        $resp = new EvaluacionPreguntasController();
                        return $resp->handleCrudOperation($request);
                    }
                     else {
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'No se ha podido guardar la información', 'data' => null],
                            500
                        );
                    }
                case 'ELIMINAR':
                    $data = DB::select('exec eval.Sp_DEL_bancoPreguntasxiCredId ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iBancoId > 0) {
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
                    $data = DB::select('exec eval.Sp_UPD_bancoPreguntasxiCredId ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iBancoId > 0) {
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
