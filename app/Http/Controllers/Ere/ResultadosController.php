<?php

namespace App\Http\Controllers\ere;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class ResultadosController extends Controller
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

    public function guardarResultadosxiEstudianteIdxiResultadoRptaEstudiante(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iResultadoId',
                'iEstudianteId',
                'iResultadoRptaEstudiante',
                'iIieeId',
                'iEvaluacionId',
                'iYAcadId',
                'iPreguntaId',
                'iCursoNivelGradId',
                'iMarcado'
            ];

            $request = $this->validateRequest($request, $fieldsToDecode, false);
            $parametros = [
                 $request->iResultadoId               ??  NULL
                ,$request->iEstudianteId              ??  NULL    
                ,$request->iResultadoRptaEstudiante   ??  NULL
                ,$request->iIieeId                    ??  NULL
                ,$request->iEvaluacionId              ??  NULL
                ,$request->iYAcadId                   ??  NULL
                ,$request->iPreguntaId                ??  NULL
                ,$request->iCursoNivelGradId          ??  NULL    
                ,$request->iMarcado                   ??  NULL    
            ];
            $data = DB::select('exec ere.SP_INS_UPD_GuardaRptasEvaluacion ?,?,?,?,?,?,?,?,?', $parametros);
            if ($data[0]->iResultadoId > 0) {
                return new JsonResponse(
                    ['validated' => true, 'message' => 'Se guardó exitosamente', 'data' => null],
                    200
                );
            } else {
                return new JsonResponse(
                    ['validated' => false, 'message' => 'No se ha podido actualizar la información', 'data' => null],
                    500
                );
            }
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                500
            );
        }
    }
}
