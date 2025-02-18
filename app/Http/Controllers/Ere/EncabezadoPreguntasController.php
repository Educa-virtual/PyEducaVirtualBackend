<?php

namespace App\Http\Controllers\Ere;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class EncabezadoPreguntasController extends Controller
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

            'iEncabPregId',
            'iNivelGradoId',
            'iCursosNivelGradId',
            'iEspecialistaIdDRE',
        ];

        foreach ($fieldsToDecode as $field) {
            $request[$field] = $this->decodeValue($request->$field);
        }

        return [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iEncabPregId                ??  NULL,
            $request->iNivelGradoId               ??  NULL,
            $request->cEncabPregTitulo            ??  NULL,
            $request->cEncabPregContenido         ??  NULL,
            $request->iCursosNivelGradId          ??  NULL,
            $request->iEspecialistaIdDRE          ??  NULL,

            $request->iCredId                     ??  NULL
        ];
    }

    private function encodeFields($item)
    {
        $fieldsToEncode = [
            'iEncabPregId',
            'iNivelGradoId',
            'iCursosNivelGradId',
            'iEspecialistaIdDRE'
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
                case 'GUARDAR-ENCABEZADO-PREGUNTAS':
                    $data = DB::select('exec ere.Sp_INS_encabezadoPreguntas ?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iEncabPregId > 0) {
                        $request['opcion'] = 'GUARDAR-PREGUNTAS';
                        $request['iEncabPregId'] = $data[0]->iEncabPregId;
                        $resp = new PreguntasController();
                        return $resp->handleCrudOperation($request);
                    } else {
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'No se ha podido guardar la información', 'data' => null],
                            500
                        );
                    }
                    break;
                case 'ACTUALIZARxiEncabPregId':
                    $data = DB::select('exec ere.Sp_UPD_encabezadoPreguntas ?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iEncabPregId > 0) {
                        $request['opcion'] = 'ACTUALIZARxiDesempenoId';
                        $resp = new DesempenosController();
                        return $resp->handleCrudOperation($request);
                    } else {
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'No se ha podido actualizar la información', 'data' => null],
                            500
                        );
                    }
                    break;
            }
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54) ?? $e->getMessage(), 'data' => []],
                500
            );
        }
    }
}
