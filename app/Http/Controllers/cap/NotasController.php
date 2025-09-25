<?php

namespace App\Http\Controllers\cap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;
use App\Http\Controllers\grl\PersonasController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NotasController extends Controller
{
    public function obtenerNotaEstudiantes(Request $request, $iCapacitacionId)
    {
        $request->merge(['iCapacitacionId' => $iCapacitacionId]);

        $validator = Validator::make($request->all(), [
            'iCapacitacionId' => ['required'],
        ], [
            'iCapacitacionId.required' => 'No se encontró el identificador de la capacitación',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iCapacitacionId',
            ];

            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCapacitacionId               ??  NULL
            ];

            $data = DB::select(
                'exec cap.SP_SEL_notasxiCapacitacionId 
                    @_iCapacitacionId=?',
                $parametros
            );

            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);

            return new JsonResponse(
                ['validated' => true, 'message' => 'Se ha obtenido exitosamente ', 'data' => ($data)],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function calificarNotaEstudiantes(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'iInscripId' => ['required'],
            'iCapacitacionId' => ['required'],
        ], [
            'iInscripId.required' => 'No se encontró el identificador de la inscripciòn',
            'iCapacitacionId.required' => 'No se encontró el identificador de la capacitación',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iInscripId',
                'iCapacitacionId',
                'iCredId'
            ];

            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iInscripId               ??  NULL,
                $request->iCapacitacionId          ??  NULL,
                $request->iNroNota                 ??  NULL,
                $request->cConclusion              ??  NULL,
                $request->iCredId                  ??  NULL
            ];

            $data = DB::select(
                'exec cap.SP_INS_notasxiInscripIdxiCapacitacionId 
                    @_iInscripId=?,
                    @_iCapacitacionId=?,
                    @_iNroNota=?,
                    @_cConclusion=?,
                    @_iCredId=?
                    ',
                $parametros
            );

            if ($data[0]->iNotaId > 0) {
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
}
