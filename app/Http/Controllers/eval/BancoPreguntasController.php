<?php

namespace App\Http\Controllers\eval;

use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BancoPreguntasController extends Controller
{
    public function obtenerBancoPreguntasxiEvaluacionIdxiCursoIdxiDocenteId(Request $request, $iEvaluacionId, $iCursoId, $iDocenteId)
    {
        $request->merge(['iEvaluacionId' => $iEvaluacionId]);
        $request->merge(['iCursoId' => $iCursoId]);
        $request->merge(['iDocenteId' => $iDocenteId]);

        $validator = Validator::make($request->all(), [
            'iEvaluacionId' => ['required'],
            'iCursoId' => ['required'],
            'iDocenteId' => ['required'],
        ], [
            'iEvaluacionId.required' => 'No se encontró el identificador iCursoId',
            'iCursoId.required' => 'No se encontró el identificador iCursoId',
            'iDocenteId.required' => 'No se encontró el identificador iDocenteId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iEvaluacionId',
                'iCursoId',
                'iDocenteId',
                'iCredId',
            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iEvaluacionId ?? null,
                $request->iCursoId ?? null,
                $request->iDocenteId ?? null,
                $request->iCredId ?? null,
            ];

            $data = DB::select(
                'exec eval.SP_SEL_bancoPreguntasxiEvaluacionIdxiCursoIdxiDocenteId
                    @_iEvaluacionId=?,   
                    @_iCursoId=?,   
                    @_iDocenteId=?,   
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

    public function importarBancoPreguntas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iCursoId' => ['required'],
            // 'iDocenteId' => ['required'],
            'iEvaluacionId' => ['required'],
        ], [
            'iCursoId.required' => 'No se encontró el identificador iCursoId',
            // 'iDocenteId.required' => 'No se encontró el identificador iDocenteId',
            'iEvaluacionId.required' => 'No se encontró el identificador iEvaluacionId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iCursoId',
                'iDocenteId',
                'iEvaluacionId',
                'iCredId',
            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCursoId ?? null,
                $request->iDocenteId ?? null,
                $request->iEvaluacionId ?? null,
                $request->jsonData ?? null,
                $request->iCredId ?? null,
            ];

            $data = DB::select(
                'exec eval.SP_INS_importarBancoPreguntas
                    @_iCursoId=?,   
                    @_iDocenteId=?,   
                    @_iEvaluacionId=?,   
                    @_jsonData=?,   
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iEvaluacionId > 0) {
                $message = 'Se ha importado exitosamente';

                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => []],
                    Response::HTTP_OK
                );
            } else {
                $message = 'No se ha podido importar';

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

    public function guardarBancoPreguntas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iDocenteId' => ['required'],
            'iTipoPregId' => ['required'],
            'iCursoId' => ['required'],
            'iNivelCicloId' => ['required'],
            'iNivelGradoId' => ['required'],
            'cBancoPregunta' => ['required'],
        ], [
            'iDocenteId.required' => 'No se encontró el identificador iDocenteId',
            'iTipoPregId.required' => 'No se encontró el identificador iTipoPregId',
            'iCursoId.required' => 'No se encontró el identificador iCursoId',
            'iNivelCicloId.required' => 'No se encontró el identificador iNivelCicloId',
            'iNivelGradoId.required' => 'No se encontró el identificador iNivelGradoId',
            'cBancoPregunta.required' => 'Debe ingresar el enunciado de la pregunta',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iDocenteId',
                'iTipoPregId',
                'iCursoId',
                'iNivelCicloId',
                'idEncabPregId',
                'iCredId',
            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iDocenteId ?? null,
                $request->iTipoPregId ?? null,
                $request->iCursoId ?? null,
                $request->iNivelCicloId ?? null,
                $request->idEncabPregId ?? null,
                $request->cBancoPregunta ?? null,
                $request->cBancoTextoAyuda ?? null,
                $request->jsonAlternativas ?? null,
                $request->iCredId ?? null,
                $request->header('iCredEntPerfId') ?? null,
                $request->iNivelGradoId ?? null,
            ];

            $data = DB::select(
                'exec eval.SP_INS_bancoPreguntas 
                    @_iDocenteId=?,   
                    @_iTipoPregId=?,   
                    @_iCursoId=?,   
                    @_iNivelCicloId=?,   
                    @_idEncabPregId=?,   
                    @_cBancoPregunta=?,   
                    @_cBancoTextoAyuda=?,   
                    @_jsonAlternativas=?,   
                    @_iCredId=?,
                    @_iCredEntPerfId=?,
                    @_iNivelGradoId=?',
                $parametros
            );

            if ($data[0]->iBancoId > 0) {
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

    public function actualizarBancoPreguntasxiBancoId(Request $request, $iBancoId)
    {
        $request->merge(['iBancoId' => $iBancoId]);

        $validator = Validator::make($request->all(), [
            'iBancoId' => ['required'],
            'iTipoPregId' => ['required'],
            'cBancoPregunta' => ['required'],
        ], [
            'iBancoId.required' => 'No se encontró el identificador iBancoId',
            'iTipoPregId.required' => 'No se encontró el identificador iTipoPregId',
            'cBancoPregunta.required' => 'Debe ingresar el enunciado de la pregunta',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iBancoId',
                'iTipoPregId',
                'iCredId',
            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iBancoId ?? null,
                $request->iTipoPregId ?? null,
                $request->cBancoPregunta ?? null,
                $request->cBancoTextoAyuda ?? null,
                $request->jsonAlternativas ?? null,
                $request->iCredId ?? null,
            ];

            $data = DB::select(
                'exec eval.SP_UPD_bancoPreguntasxiBancoId 
                    @_iBancoId=?,   
                    @_iTipoPregId=?,   
                    @_cBancoPregunta=?,   
                    @_cBancoTextoAyuda=?,   
                    @_jsonAlternativas=?,   
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iBancoId > 0) {
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

    public function eliminarBancoPreguntasxiBancoId(Request $request, $iBancoId)
    {
        $request->merge(['iBancoId' => $iBancoId]);

        $validator = Validator::make($request->all(), [
            'iBancoId' => ['required'],
        ], [
            'iBancoId.required' => 'No se encontró el identificador iBancoId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iBancoId',
                'iCredId',
            ];

            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iBancoId ?? null,
                $request->iCredId ?? null,
            ];
            $data = DB::select(
                'exec eval.SP_DEL_bancoPreguntasxiBancoId
                    @_iBancoId=?,    
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iBancoId > 0) {
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

    public function handleCrudOperation(Request $request)
    {
        // $fieldsToDecode = [
        //         'iBancoId',
        //     ];
        // $parametros = VerifyHash::validateRequest($request, $fieldsToDecode);
        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iBancoId ?? null,
            $request->iDocenteId ?? null,
            $request->iTipoPregId ?? null,
            $request->iCurrContId ?? null,
            $request->dtBancoCreacion ?? null,
            $request->cBancoPregunta ?? null,
            $request->dtBancoTiempo ?? null,
            $request->cBancoTextoAyuda ?? null,
            $request->nBancoPuntaje ?? null,
            $request->iEstado ?? null,
            $request->iSesionId ?? null,
            $request->dtCreado ?? null,
            $request->dtActualizado ?? null,
            $request->idEncabPregId ?? null,
            $request->iCursoId ?? null,
            $request->iNivelCicloId ?? null,

            $request->iCredId ?? null,
        ];

        try {
            switch ($request->opcion) {
                case 'CONSULTARxiEvaluacionId':
                case 'CONSULTARxiBancoId':
                    $data = DB::select('exec eval.Sp_SEL_bancoPreguntasxiCredId ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

                    // $data = $this->encodeId($data);
                    return new JsonResponse(
                        ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data],
                        200
                    );
                    break;
                case 'GUARDARxBancoPreguntas':
                    $data = DB::select('exec eval.Sp_INS_bancoPreguntasxiCredId ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iBancoId > 0) {
                        if ($request->iTipoPregId < 3) {
                            $request['iBancoId'] = VerifyHash::encodexId($data[0]->iBancoId);
                            $resp = new EvaluacionPreguntasController;

                            return $resp->handleCrudOperation($request);
                        } else {
                            return new JsonResponse(
                                ['validated' => true, 'message' => 'Se guardó la información', 'data' => $data],
                                200
                            );
                        }
                    } else {
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
                case 'ACTUALIZARxBancoPreguntas':
                    $data = DB::select('exec eval.Sp_UPD_bancoPreguntasxiCredId ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iBancoId > 0) {
                        if ($request->iTipoPregId < 3) {
                            $request['iBancoId'] = VerifyHash::encodexId($data[0]->iBancoId);
                            $resp = new EvaluacionPreguntasController;

                            return $resp->handleCrudOperation($request);
                        } else {
                            return new JsonResponse(
                                ['validated' => true, 'message' => 'Se actualizó la información', 'data' => $data],
                                200
                            );
                        }
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
