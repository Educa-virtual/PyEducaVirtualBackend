<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\VerifyHash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ReunionVirtualesController extends Controller
{
    public function guardarReunionVirtuales(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cRVirtualTema' => ['required'],
            'dtRVirtualInicio' => ['required', 'date'],
            'dtRVirtualFin' => ['required', 'date', 'after:dtRVirtualInicio'],
            'cRVirtualUrlJoin' => ['required', 'url'],
            'iProgActId' => ['required']
        ], [
            'cRVirtualTema.required' => 'No ingresó tema de la reunión virtual',
            'dtRVirtualInicio.required' => 'La fecha y hora de inicio es obligatoria',
            'dtRVirtualInicio.date' => 'La fecha de inicio no es válida.',
            'dtRVirtualFin.required' => 'La fecha y hora de fin es obligatoria',
            'dtRVirtualFin.date' => 'La fecha de fin no es válida.',
            'dtRVirtualFin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            'cRVirtualUrlJoin.required' => 'No ingresó la URL de la reunión virtual',
            'cRVirtualUrlJoin.url' => 'La URL de la reunión virtual no es válida.',
            'iProgActId.required' => 'No se encontro el identificador iProgActId',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iProgActId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);
            $parametros = [
                $request->cRVirtualTema               ??  NULL,
                $request->dtRVirtualInicio            ??  NULL,
                $request->dtRVirtualFin               ??  NULL,
                $request->cRVirtualUrlJoin            ??  NULL,
                $request->iProgActId                  ??  NULL,
                $request->iCredId                     ??  NULL
            ];

            $data = DB::select(
                'exec aula.SP_INS_reunionVirtuales 
                    @_cRVirtualTema=?, 
                    @_dtRVirtualInicio=?, 
                    @_dtRVirtualFin=?, 
                    @_cRVirtualUrlJoin=?, 
                    @_iProgActId=?, 
                    @_iCredId=?',
                $parametros
            );
            if ($data[0]->iRVirtualId > 0) {
                return new JsonResponse(
                    ['validated' => true, 'message' => 'Se ha guardado exitosamente ', 'data' => null],
                    Response::HTTP_OK
                );
            } else {
                return new JsonResponse(
                    ['validated' => false, 'message' => 'No se ha podido guardar', 'data' => null],
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

    public function actualizarReunionVirtuales(Request $request, $iRVirtualId)
    {
        $request->merge(['iRVirtualId' => $iRVirtualId]);

        $validator = Validator::make($request->all(), [
            'iRVirtualId' => ['required'],
            'cRVirtualTema' => ['required'],
            'dtRVirtualInicio' => ['required', 'date'],
            'dtRVirtualFin' => ['required', 'date', 'after:dtRVirtualInicio'],
            'cRVirtualUrlJoin' => ['required', 'url'],
        ], [
            'iRVirtualId.required' => 'No se encontro el identificador iRVirtualId',
            'cRVirtualTema.required' => 'No ingresó tema de la reunión virtual',
            'dtRVirtualInicio.required' => 'La fecha y hora de inicio es obligatoria',
            'dtRVirtualInicio.date' => 'La fecha de inicio no es válida.',
            'dtRVirtualFin.required' => 'La fecha y hora de fin es obligatoria',
            'dtRVirtualFin.date' => 'La fecha de fin no es válida.',
            'dtRVirtualFin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            'cRVirtualUrlJoin.required' => 'No ingresó la URL de la reunión virtual',
            'cRVirtualUrlJoin.url' => 'La URL de la reunión virtual no es válida.',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iRVirtualId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);
            $parametros = [
                $request->iRVirtualId                  ??  NULL,
                $request->cRVirtualTema               ??  NULL,
                $request->dtRVirtualInicio            ??  NULL,
                $request->dtRVirtualFin               ??  NULL,
                $request->cRVirtualUrlJoin            ??  NULL,
                $request->iCredId                     ??  NULL
            ];

            $data = DB::select(
                'exec aula.SP_UPD_reunionVirtuales 
                    @_iRVirtualId=?, 
                    @_cRVirtualTema=?, 
                    @_dtRVirtualInicio=?, 
                    @_dtRVirtualFin=?, 
                    @_cRVirtualUrlJoin=?, 
                    @_iCredId=?',
                $parametros
            );
            if ($data[0]->iRVirtualId > 0) {
                return new JsonResponse(
                    ['validated' => true, 'message' => 'Se ha actualizado exitosamente ', 'data' => null],
                    Response::HTTP_OK
                );
            } else {
                return new JsonResponse(
                    ['validated' => false, 'message' => 'No se ha podido actualizar', 'data' => null],
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
    public function eliminarReunionVirtuales(Request $request, $iRVirtualId)
    {
        $request->merge(['iRVirtualId' => $iRVirtualId]);

        $validator = Validator::make($request->all(), [
            'iRVirtualId' => ['required']
        ], [
            'iRVirtualId.required' => 'No se encontro el identificador iRVirtualId'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iRVirtualId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);
            $parametros = [
                $request->iRVirtualId                  ??  NULL,
                $request->iCredId                     ??  NULL
            ];

            $data = DB::select(
                'exec aula.SP_DEL_reunionVirtuales 
                    @_iRVirtualId=?, 
                    @_iCredId=?',
                $parametros
            );
            if ($data[0]->iRVirtualId > 0) {
                return new JsonResponse(
                    ['validated' => true, 'message' => 'Se ha eliminado exitosamente ', 'data' => null],
                    Response::HTTP_OK
                );
            } else {
                return new JsonResponse(
                    ['validated' => false, 'message' => 'No se ha podido eliminar', 'data' => null],
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
