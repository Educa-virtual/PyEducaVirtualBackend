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
            'cEvalPregPregunta' => ['required']
        ], [
            'iEvaluacionId.required' => 'No se encontró el identificador iEvaluacionId',
            'iDocenteId.required' => 'No se encontró el identificador iDocenteId',
            'iTipoPregId.required' => 'No se encontró el identificador iTipoPregId',
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
    public function actualizarEvaluacionPreguntasxiEvalPregId(Request $request, $iEvalPregId)
    {
        $request->merge(['iEvalPregId' => $iEvalPregId]);

        $validator = Validator::make($request->all(), [
            'iEvalPregId' => ['required'],
            'iTipoPregId' => ['required'],
            'cEvalPregPregunta' => ['required']
        ], [
            'iEvalPregId.required' => 'No se encontró el identificador iEvalPregId',
            'iTipoPregId.required' => 'No se encontró el identificador iTipoPregId',
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
                'iEvalPregId',
                'iTipoPregId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iEvalPregId                 ??  NULL,
                $request->iTipoPregId                 ??  NULL,
                $request->cEvalPregPregunta           ??  NULL,
                $request->cEvalPregTextoAyuda         ??  NULL,
                $request->jsonAlternativas            ??  NULL,
                $request->iCredId                     ??  NULL
            ];

            $data = DB::select(
                'exec eval.SP_UPD_evaluacionPreguntasxiEvalPregId 
                    @_iEvalPregId=?,   
                    @_iTipoPregId=?,   
                    @_cEvalPregPregunta=?,   
                    @_cEvalPregTextoAyuda=?,   
                    @_jsonAlternativas=?,   
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iEvalPregId > 0) {
                $message = 'Se ha actualizado exitosamente';
                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => []],
                    Response::HTTP_OK
                );
            } else {
                $message = 'No se ha podido actualizar';
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

    public function eliminarEvaluacionPreguntasxiEvalPregId(Request $request, $iEvalPregId)
    {
        $request->merge(['iEvalPregId' => $iEvalPregId]);

        $validator = Validator::make($request->all(), [
            'iEvalPregId' => ['required'],
        ], [
            'iEvalPregId.required' => 'No se encontró el identificador iEvalPregId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iEvalPregId',
                'iCredId'
            ];

            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iEvalPregId      ??  NULL,
                $request->iCredId      ??  NULL
            ];
            $data = DB::select(
                'exec eval.SP_DEL_evaluacionPreguntasxiEvalPregId
                    @_iEvalPregId=?,    
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iEvalPregId > 0) {
                $message = 'Se ha eliminado exitosamente';
                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => []],
                    Response::HTTP_OK
                );
            } else {
                $message = 'No se ha podido eliminar';
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

    public function obtenerEvaluacionPreguntasxiEvaluacionIdxiEstudianteId(Request $request, $iEvaluacionId, $iEstudianteId)
    {
        $request->merge(['iEvaluacionId' => $iEvaluacionId]);
        $request->merge(['iEstudianteId' => $iEstudianteId]);

        try {
            $fieldsToDecode = [
                'iEvaluacionId',
                'iEstudianteId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iEvaluacionId    ??  NULL,
                $request->iEstudianteId    ??  NULL,
                $request->iCredId          ??  NULL
            ];

            $data = DB::select(
                'exec eval.SP_SEL_evaluacionPreguntasxiEvaluacionIdxiEstudianteId
                    @_iEvaluacionId=?,   
                    @_iEstudianteId=?,   
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
}
