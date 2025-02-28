<?php

namespace App\Http\Controllers\ere;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class DesempenosController extends ApiController
{
    protected  $alternativaPreguntaRespository;

    public function obtenerDesempenos(Request $request)
    {

        $params = [
            $request->iCursoId,
            $request->iNivelTipoId,
            $request->iEvaluacionId ?? 0,
            $request->iCompCursoId ?? 0,
            $request->iCapacidadId ?? 0
        ];


        try {
            $desempenos = DB::select(
                'EXEC acad.Sp_SEL_desempenos @_iCursoId = ?
                , @_InivelTipoId  = ?
                , @_iEvaluacionId = ?
                , @_iCompCursoId = ?
                , @_iCapacidadId = ?',
                $params
            );

            return $this->successResponse($desempenos, 'Datos obtenidos correctamente');
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al obtener los datos');
        }
    }

    //Estructura : Jhonny
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

            'iDesempenoId',
            'iEvaluacionId',
            'iCompCursoId',
            'iCapacidadId',
        ];

        foreach ($fieldsToDecode as $field) {
            $request[$field] = $this->decodeValue($request->$field);
        }

        return [
            $request->opcion,
            $request->valorBusqueda ?? NULL,

            $request->iDesempenoId              ??  NULL,
            $request->iEvaluacionId             ??  NULL,
            $request->iCompCursoId              ??  NULL,
            $request->iCapacidadId              ??  NULL,
            $request->cDesempenoDescripcion     ??  NULL,
            $request->cDesempenoConocimiento    ??  NULL,

            $request->iCredId               ??  NULL
        ];
    }

    private function encodeFields($item)
    {
        $fieldsToEncode = [
            'iDesempenoId',
            'iEvaluacionId',
            'iCompCursoId',
            'iCapacidadId',
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
        $request['valorBusqueda'] = $request->opcion === 'ACTUALIZARxiDesempenoId' ? $request['iCompetenciaId'] : null;
        $parametros = $this->validateRequest($request);

        try {
            switch ($request->opcion) {
                case 'ACTUALIZARxiDesempenoId':
                    $data = DB::select('exec ere.Sp_UPD_desempenos ?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iDesempenoId > 0) {
                        $request['opcion'] = $request['iPreguntaId'] ? 'ACTUALIZARxiPreguntaId' : 'GUARDAR';
                        $request['iDesempenoId'] = $data[0]->iDesempenoId;
                        $resp = new PreguntasController();
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
                ['validated' => false, 'message' => $e->getMessage(), 'data' => []],
                500
            );
        }
    }
}
