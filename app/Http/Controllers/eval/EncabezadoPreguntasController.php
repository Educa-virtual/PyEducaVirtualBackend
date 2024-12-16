<?php

namespace App\Http\Controllers\eval;

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
            'idEncabPregId',
            'iDocenteId',
            'iNivelCicloId',
            'iCursoId'
        ];

        foreach ($fieldsToDecode as $field) {
            $request[$field] = $this->decodeValue($request->$field);
        }

        return [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->idEncabPregId ?? null,
            $request->iDocenteId ?? null,
            $request->iNivelCicloId ?? null,
            $request->iCursoId ?? null,
            $request->cEncabPregTitulo ?? null,
            $request->cEncabPregContenido ?? null,

            $request->iCredId   ?? null
        ];
    }

    private function encodeFields($item)
    {
        $fieldsToEncode = [
            'idEncabPregId',
            'iDocenteId',
            'iNivelCicloId',
            'iCursoId'
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
                    $data = DB::select('exec eval.Sp_SEL_encabezadoPreguntas ?,?,?,?,?,?,?,?,?', $parametros);
                    $data = $this->encodeId($data);
                    return new JsonResponse(
                        ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data],
                        200
                    );
                    break;
                case 'GUARDARxEncabezadoPreguntas':
                    $data = DB::select('exec eval.Sp_INS_encabezadoPreguntas ?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->idEncabPregId > 0) {
                       // $data = $this->encodeId($data);
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
                    $data = DB::select('exec eval.Sp_DEL_encabezadoPreguntas ?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->idEncabPregId > 0) {
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
                    $data = DB::select('exec eval.Sp_UPD_encabezadoPreguntas ?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->idEncabPregId > 0) {
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
