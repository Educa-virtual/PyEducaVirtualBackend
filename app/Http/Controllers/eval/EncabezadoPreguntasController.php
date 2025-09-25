<?php

namespace App\Http\Controllers\eval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EncabezadoPreguntasController extends Controller
{
    public function guardarEncabezadoPreguntas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iEvaluacionId' => ['required'],
            'iDocenteId' => ['required'],
            'cEncabPregTitulo' => ['required'],
            'cEncabPregContenido' => ['required']
        ], [
            'iEvaluacionId.required' => 'No se encontró el identificador iEvaluacionId',
            'iDocenteId.required' => 'No se encontró el identificador iDocenteId',
            'cEncabPregTitulo.required' => 'Debe ingresar el título',
            'cEncabPregContenido.required' => 'Debe ingresar la descripción',
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
                'iNivelCicloId',
                'iCursoId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iEvaluacionId               ??  NULL,
                $request->iDocenteId                  ??  NULL,
                $request->iNivelCicloId               ??  NULL,
                $request->iCursoId                    ??  NULL,
                $request->cEncabPregTitulo            ??  NULL,
                $request->cEncabPregContenido         ??  NULL,
                $request->iCredId                     ??  NULL
            ];


            $data = DB::select(
                'exec eval.SP_INS_encabezadoPreguntasxiEvaluacionId
                    @_iEvaluacionId=?,   
                    @_iDocenteId=?,   
                    @_iNivelCicloId=?,   
                    @_iCursoId=?,   
                    @_cEncabPregTitulo=?,   
                    @_cEncabPregContenido=?,   
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->idEncabPregId > 0) {
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

    public function eliminarEncabezadoPreguntasxiEvalPregId(Request $request, $idEncabPregId)
    {
        $request->merge(['idEncabPregId' => $idEncabPregId]);

        $validator = Validator::make($request->all(), [
            'idEncabPregId' => ['required'],
        ], [
            'idEncabPregId.required' => 'No se encontró el identificador idEncabPregId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'idEncabPregId',
                'iCredId'
            ];

            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->idEncabPregId      ??  NULL,
                $request->iCredId      ??  NULL
            ];
            $data = DB::select(
                'exec eval.SP_DEL_encabezadoPreguntasxidEncabPregId
                    @_idEncabPregId=?,    
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->idEncabPregId > 0) {
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

    public function actualizarEncabezadoPreguntasxiEvalPregId(Request $request, $idEncabPregId)
    {
        $request->merge(['idEncabPregId' => $idEncabPregId]);
        $validator = Validator::make($request->all(), [
            'idEncabPregId' => ['required'],
            'cEncabPregTitulo' => ['required'],
            'cEncabPregContenido' => ['required'],
        ], [
            'idEncabPregId.required' => 'No se encontró el identificador idEncabPregId',
            'cEncabPregTitulo.required' => 'Debe ingresar el título',
            'cEncabPregContenido.required' => 'Debe ingresar la descripción',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'idEncabPregId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->idEncabPregId               ??  NULL,
                $request->cEncabPregTitulo            ??  NULL,
                $request->cEncabPregContenido         ??  NULL,
                $request->iCredId                     ??  NULL
            ];

            $data = DB::select(
                'exec eval.SP_UPD_evaluacionPreguntasxidEncabPregId
                    @_idEncabPregId=?,   
                    @_cEncabPregTitulo=?,   
                    @_cEncabPregContenido=?,     
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->idEncabPregId > 0) {
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

    public function guardarBancoEncabezadoPreguntas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iDocenteId' => ['required'],
            'iCursoId' => ['required'],
            'iNivelCicloId' => ['required'],
            'cEncabPregTitulo' => ['required'],
            'cEncabPregContenido' => ['required']
        ], [
            'iDocenteId.required' => 'No se encontró el identificador iDocenteId',
            'iCursoId.required' => 'No se encontró el identificador iCursoId',
            'iNivelCicloId.required' => 'No se encontró el identificador iNivelCicloId',
            'cEncabPregTitulo.required' => 'Debe ingresar el título',
            'cEncabPregContenido.required' => 'Debe ingresar la descripción',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iDocenteId',
                'iNivelCicloId',
                'iCursoId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iDocenteId                  ??  NULL,
                $request->iNivelCicloId               ??  NULL,
                $request->iCursoId                    ??  NULL,
                $request->cEncabPregTitulo            ??  NULL,
                $request->cEncabPregContenido         ??  NULL,
                $request->iCredId                     ??  NULL
            ];

            $data = DB::select(
                'exec eval.SP_INS_encabezadoBancoPreguntas
                    @_iDocenteId=?,   
                    @_iNivelCicloId=?,   
                    @_iCursoId=?,   
                    @_cEncabPregTitulo=?,   
                    @_cEncabPregContenido=?,   
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->idEncabPregId > 0) {
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

    public function actualizarBancoEncabezadoPreguntasxidEncabPregId(Request $request, $idEncabPregId)
    {
        $request->merge(['idEncabPregId' => $idEncabPregId]);

        $validator = Validator::make($request->all(), [
            'idEncabPregId' => ['required'],
            'cEncabPregTitulo' => ['required'],
            'cEncabPregContenido' => ['required'],
        ], [
            'idEncabPregId.required' => 'No se encontró el identificador idEncabPregId',
            'cEncabPregTitulo.required' => 'Debe ingresar el título',
            'cEncabPregContenido.required' => 'Debe ingresar la descripción',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'idEncabPregId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->idEncabPregId               ??  NULL,
                $request->cEncabPregTitulo            ??  NULL,
                $request->cEncabPregContenido         ??  NULL,
                $request->iCredId                     ??  NULL
            ];

            $data = DB::select(
                'exec eval.SP_UPD_encabezadoBancoPreguntasxidEncabPregId
                    @_idEncabPregId=?,   
                    @_cEncabPregTitulo=?,   
                    @_cEncabPregContenido=?,     
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->idEncabPregId > 0) {
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

    public function eliminarBancoEncabezadoPreguntasxidEncabPregId(Request $request, $idEncabPregId)
    {
        $request->merge(['idEncabPregId' => $idEncabPregId]);

        $validator = Validator::make($request->all(), [
            'idEncabPregId' => ['required'],
        ], [
            'idEncabPregId.required' => 'No se encontró el identificador idEncabPregId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'idEncabPregId',
                'iCredId'
            ];

            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->idEncabPregId      ??  NULL,
                $request->iCredId      ??  NULL
            ];
            $data = DB::select(
                'exec eval.SP_DEL_encabezadoBancoPreguntasxidEncabPregId
                    @_idEncabPregId=?,    
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->idEncabPregId > 0) {
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
