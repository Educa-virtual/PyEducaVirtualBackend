<?php

namespace App\Http\Controllers\eval;

use App\Http\Controllers\Controller;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BancoAlternativasController extends Controller
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
            'iBancoAltId',
            'iBancoId',
        ];

        foreach ($fieldsToDecode as $field) {
            $request[$field] = $this->decodeValue($request->$field);
        }

        return [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iBancoAltId ?? null,
            $request->iBancoId ?? null,
            $request->cBancoAltLetra ?? null,
            $request->cBancoAltDescripcion ?? null,
            $request->bBancoAltRptaCorrecta ?? null,
            $request->cBancoAltExplicacionRpta ?? null,

            $request->iCredId ?? null,
        ];
    }

    private function encodeFields($item)
    {
        $fieldsToEncode = [
            'idEncabPregId',
            'iBancoAltId',
            'iBancoId',
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
                    $data = DB::select('exec eval.Sp_SEL_bancoAlternativas ?,?,?,?,?,?,?,?,?', $parametros);
                    $data = $this->encodeId($data);

                    return new JsonResponse(
                        ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data],
                        200
                    );
                    break;
                case 'GUARDARxBancoPreguntas':
                    foreach ($request->alternativas as $key => $value) {

                        $json_alternativas = [
                            'GUARDARxBancoPreguntas',
                            '-',

                            $value['iBancoAltId'] ?? null,
                            $request->iBancoId ?? null,
                            $value['cBancoAltLetra'] ?? null,
                            $value['cBancoAltDescripcion'] ?? null,
                            $value['bBancoAltRptaCorrecta'] ?? null,
                            $value['cBancoAltExplicacionRpta'] ?? null,

                            $request->iCredId ?? null,
                        ];
                        $data = DB::select('exec eval.Sp_INS_bancoAlternativas ?,?,?,?,?,?,?,?,?', $json_alternativas);
                    }
                    if ($data[0]->iBancoAltId > 0) {
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'Se guardó la información', 'data' => null],
                            200
                        );
                    } else {
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'No se ha podido guardar la información', 'data' => null],
                            500
                        );
                    }
                    break;
                case 'ELIMINAR':
                    $data = DB::select('exec eval.Sp_DEL_bancoAlternativas ?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iBancoAltId > 0) {
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
                case 'ACTUALIZARxBancoPreguntas':
                    DB::update("
                    UPDATE eval.banco_alternativas
                    SET 
                    iEstado = 0
                    WHERE 
                    iBancoId = '".$request->iBancoId."'
                    ");

                    foreach ($request->alternativas as $key => $value) {
                        if ($value['iBancoAltId'] > 0) {
                            $json_alternativas = [
                                'ACTUALIZARxBancoPreguntas',
                                '-',

                                $value['iBancoAltId'] ?? null,
                                $request->iBancoId ?? null,
                                $value['cBancoAltLetra'] ?? null,
                                $value['cBancoAltDescripcion'] ?? null,
                                $value['bBancoAltRptaCorrecta'] ?? null,
                                $value['cBancoAltExplicacionRpta'] ?? null,

                                $request->iCredId ?? null,
                            ];
                            $data = DB::select('exec eval.Sp_UPD_bancoAlternativas ?,?,?,?,?,?,?,?,?', $json_alternativas);
                        } else {
                            $json_alternativas = [
                                'GUARDARxBancoPreguntas',
                                '-',
                                $value['iBancoAltId'] ?? null,
                                $request->iBancoId ?? null,
                                $value['cBancoAltLetra'] ?? null,
                                $value['cBancoAltDescripcion'] ?? null,
                                $value['bBancoAltRptaCorrecta'] ?? null,
                                $value['cBancoAltExplicacionRpta'] ?? null,

                                $request->iCredId ?? null,
                            ];
                            $data = DB::select('exec eval.Sp_INS_bancoAlternativas ?,?,?,?,?,?,?,?,?', $json_alternativas);
                        }
                    }
                    if ($data[0]->iBancoAltId > 0) {
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'Se guardó la información', 'data' => null],
                            200
                        );
                    } else {
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'No se ha podido guardar la información', 'data' => null],
                            500
                        );
                    }
                    break;
            }
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => $e->getMessage(), 'data' => []],
                500
            );
        }
    }
}
