<?php

namespace App\Http\Controllers\eval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
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
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iEvaluacionId',
                'iCursoId',
                'iDocenteId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iEvaluacionId          ??  NULL,
                $request->iCursoId               ??  NULL,
                $request->iDocenteId             ??  NULL,
                $request->iCredId                ??  NULL
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
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iCursoId',
                'iDocenteId',
                'iEvaluacionId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCursoId                    ??  NULL,
                $request->iDocenteId                  ??  NULL,
                $request->iEvaluacionId               ??  NULL,
                $request->jsonData                    ??  NULL,
                $request->iCredId                     ??  NULL
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

    public function handleCrudOperation(Request $request)
    {
        // $fieldsToDecode = [
        //         'iBancoId',
        //     ];
        // $parametros = VerifyHash::validateRequest($request, $fieldsToDecode);
        $parametros = [
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
                        if ($request->iTipoPregId < 3) {
                            $request['iBancoId'] = VerifyHash::encodexId($data[0]->iBancoId);
                            $resp = new EvaluacionPreguntasController();
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
                            $resp = new EvaluacionPreguntasController();
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
