<?php

namespace App\Http\Controllers\eval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class EvaluacionPreguntasController extends Controller
{
    public function guardarEvaluacionPreguntas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iEvaluacionId' => ['required'],
            'iDocenteId' => ['required'],
            'iTipoPregId' => ['required'],
            'iCursoId' => ['required'],
            'iNivelCicloId' => ['required'],
            'cEvalPregPregunta' => ['required']
        ], [
            'iEvaluacionId.required' => 'No se encontró el identificador iEvaluacionId',
            'iDocenteId.required' => 'No se encontró el identificador iDocenteId',
            'iTipoPregId.required' => 'No se encontró el identificador iTipoPregId',
            'iCursoId.required' => 'No se encontró el identificador iCursoId',
            'iNivelCicloId.required' => 'No se encontró el identificador iNivelCicloId',
            'cEvalPregPregunta.required' => 'Debe ingresar el enunciado de la pregunta',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iEvaluacionId',
                'iDocenteId',
                'iTipoPregId',
                'iCursoId',
                'iNivelCicloId',
                'idEncabPregId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iEvaluacionId               ??  NULL,
                $request->iDocenteId                  ??  NULL,
                $request->iTipoPregId                 ??  NULL,
                $request->iCursoId                    ??  NULL,
                $request->iNivelCicloId               ??  NULL,
                $request->idEncabPregId               ??  NULL,
                $request->cEvalPregPregunta           ??  NULL,
                $request->cEvalPregTextoAyuda         ??  NULL,
                $request->jsonAlternativas            ??  NULL,
                $request->iCredId                     ??  NULL
            ];

            $data = DB::select(
                'exec eval.SP_INS_evaluacionPreguntas 
                    @_iEvaluacionId=?,   
                    @_iDocenteId=?,   
                    @_iTipoPregId=?,   
                    @_iCursoId=?,   
                    @_iNivelCicloId=?,   
                    @_idEncabPregId=?,   
                    @_cEvalPregPregunta=?,   
                    @_cEvalPregTextoAyuda=?,   
                    @_jsonAlternativas=?,   
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iEvalPregId > 0) {
                $message = 'Se ha guardado exitosamente';
                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => []],
                    Response::HTTP_OK
                );
            } else {
                $message = 'No se ha podido guardar';
                return new JsonResponse(
                    ['validated' => false, 'message' => $message, 'data' => []],
                    Response::HTTP_OK
                );
            }
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
    
    public function obtenerEvaluacionPreguntasxiEvaluacionId(Request $request, $iEvaluacionId)
    {
        $request->merge(['iEvaluacionId' => $iEvaluacionId]);

        try {
            $fieldsToDecode = [
                'iEvaluacionId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iEvaluacionId    ??  NULL,
                $request->iCredId          ??  NULL
            ];

            $data = DB::select(
                'exec eval.SP_SEL_evaluacionPreguntasxiEvaluacionId
                    @_iEvaluacionId=?,   
                    @_iCredId=?',
                $parametros
            );

            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);

            return new JsonResponse(
                ['validated' => true, 'message' => 'Se ha obtenido exitosamente ', 'data' => $data],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
    public function actualizarEvaluacionPreguntasxiEvalPregId(Request $request, $iEvalPregId) {}
    public function eliminarEvaluacionPreguntasxiEvalPregId(Request $request, $iEvalPregId) {}
}
