<?php

namespace App\Http\Controllers\ere;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class EvaluacionController extends Controller
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

    public function validateRequest(Request $request, $fieldsToDecode, $completo = true)
    {
        $request->validate(
            ['opcion' => 'required'],
            ['opcion.required' => 'Hubo un problema al obtener la acción']
        );

        foreach ($fieldsToDecode as $field) {
            $request[$field] = $this->decodeValue($request->$field);
        }

        return !$completo ? $request : [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->idTipoEvalId              ??  NULL,
            $request->iNivelEvalId              ??  NULL,
            $request->dtEvaluacionCreacion      ??  NULL,
            $request->cEvaluacionNombre         ??  NULL,
            $request->cEvaluacionDescripcion    ??  NULL,
            $request->cEvaluacionUrlDrive       ??  NULL,
            $request->cEvaluacionUrlPlantilla   ??  NULL,
            $request->cEvaluacionUrlManual      ??  NULL,
            $request->cEvaluacionUrlMatriz      ??  NULL,
            $request->cEvaluacionObs            ??  NULL,
            $request->dtEvaluacionLiberarMatriz ??  NULL,
            $request->dtEvaluacionLiberarCuadernillo    ??  NULL,
            $request->dtEvaluacionLiberarResultados     ??  NULL,
            $request->iEstado                           ??  NULL,
            $request->iSesionId                         ??  NULL,
            $request->iEvaluacionId                     ??  NULL,
            $request->cEvaluacionIUrlCuadernillo        ??  NULL,
            $request->cEvaluacionUrlHojaRespuestas      ??  NULL,
            $request->dtEvaluacionFechaInicio           ??  NULL,
            $request->dtEvaluacionFechaFin              ??  NULL,

            $request->iCredId                       ??  NULL
        ];
    }

    private function encodeFields($item)
    {
        $fieldsToEncode = [
            'idTipoEvalId',
            'iNivelEvalId',
            'iEvaluacionId',
            'iBancoAltCorrecta',

            'iCursoNivelGradId',
            'iSedeId',
            'iYearId',
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
        $fieldsToDecode = [
            'valorBusqueda',

            'idTipoEvalId',
            'iNivelEvalId',
            'iEvaluacionId',
            'iBancoAltCorrecta'

        ];

        $parametros = $this->validateRequest($request, $fieldsToDecode, true);

        try {
            switch ($request->opcion) {
                case 'CONSULTARxiEvaluacionIdxiCursoNivelGradId':
                case 'CONSULTARxiEvaluacionId':
                case 'CONSULTAR-ESTADOxiEvaluacionId':
                case 'CONSULTAR-PREGUNTAS-ESTUDIANTExiEvaluacionIdxiCursoNivelGradId':
                case 'CONSULTAR-ESTADO-ULTIMO-ACTIVO':
                    $data = DB::select('exec ere.Sp_SEL_evaluacion ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    $data = $this->encodeId($data);
                    return new JsonResponse(
                        ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data],
                        200
                    );
                    break;
                case 'ELIMINARxiEvaluacionId':
                    $data = DB::update('exec ere.SP_DEL_evaluacion ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    //return $data;
                    if ($data) {
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
                    break;
            }
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                500
            );
        }
    }

    public function obtenerEstudianteAreasEvaluacion(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iEstudianteId',
                'iEvaluacionId',
                'iYAcadId'
            ];
            $parametro = $this->validateRequest($request, $fieldsToDecode, false);
            $parametros = [
                $parametro->iEstudianteId              ??  NULL,
                $parametro->iEvaluacionId              ??  NULL,
                $parametro->iYAcadId      ??  NULL
            ];
            $data = DB::select('exec ere.SP_SEL_EstudianteEvaluacion ?,?,?', $parametros);
            //$data = $this->encodeId($data);
            return new JsonResponse(
                ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data],
                200
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                500
            );
        }
    }

    public function ConsultarPreguntasxiEvaluacionIdxiCursoNivelGradIdxiEstudianteId(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iEvaluacionId',
                'iCursoNivelGradId',
                'iEstudianteId',
                'iIieeId',
                'iYAcadId'
            ];
            $parametro = $this->validateRequest($request, $fieldsToDecode, false);
            $parametros = [
                $parametro->iEvaluacionId              ??  NULL,
                $parametro->iCursoNivelGradId          ??  NULL,
                $parametro->iEstudianteId              ??  NULL,
                $parametro->iIieeId                    ??  NULL,
                $parametro->iYAcadId                   ??  NULL
            ];
            $data = DB::select('exec ere.SP_SEL_iEstudianteIdxiEvaluacionIdxiCursoNivelGradId ?,?,?,?,?', $parametros);
            //$data = $this->encodeId($data);
            return new JsonResponse(
                ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data],
                200
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                500
            );
        }
    }
}
