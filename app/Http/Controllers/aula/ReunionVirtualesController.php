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
            'dtRVirtualInicio' => ['required'],
            'dtRVirtualFin' => ['required'],
            'cRVirtualUrlJoin' => ['required', 'url'],
            'iContenidoSemId' => ['required'],
            'iActTipoId' => ['required'],
            'iYAcadId' => ['required'],

        ], [
            'cRVirtualTema.required' => 'No ingresó tema de la reunión virtual',
            'dtRVirtualInicio.required' => 'La fecha y hora de inicio es obligatoria',
            'dtRVirtualFin.required' => 'La fecha y hora de fin es obligatoria',
            'cRVirtualUrlJoin.required' => 'No ingresó la URL de la reunión virtual',
            'cRVirtualUrlJoin.url' => 'La URL de la reunión virtual no es válida.',
            'iContenidoSemId.required' => 'No se encontró el identificador iContenidoSemId',
            'iActTipoId.required' => 'No se encontró el identificador iActTipoId',
            'iYAcadId.required' => 'No se encontró el identificador del año académico',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iContenidoSemId',
                'iActTipoId',
                'idDocCursoId',
                'iCredId',
                'iCapacitacionId',
                'iYAcadId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);
            $parametros = [
                $request->cRVirtualTema               ??  NULL,
                $request->dtRVirtualInicio            ??  NULL,
                $request->dtRVirtualFin               ??  NULL,
                $request->cRVirtualUrlJoin            ??  NULL,
                $request->iContenidoSemId             ??  NULL,
                $request->iActTipoId                  ??  NULL,
                $request->idDocCursoId                ??  NULL,
                $request->iCapacitacionId             ??  NULL,
                $request->iYAcadId                    ??  NULL,
                $request->iCredId                     ??  NULL
            ];

            $data = DB::select(
                'exec aula.SP_INS_reunionVirtuales 
                    @_cRVirtualTema=?, 
                    @_dtRVirtualInicio=?, 
                    @_dtRVirtualFin=?, 
                    @_cRVirtualUrlJoin=?, 
                    @_iContenidoSemId=?,
                    @_iActTipoId=?,
                    @_idDocCursoId=?,
                    @_iCapacitacionId=?,
                    @_iYAcadId=?,
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
            'dtRVirtualInicio' => ['required'],
            'dtRVirtualFin' => ['required'],
            'cRVirtualUrlJoin' => ['required', 'url'],
        ], [
            'iRVirtualId.required' => 'No se encontró el identificador iRVirtualId',
            'cRVirtualTema.required' => 'No ingresó tema de la reunión virtual',
            'dtRVirtualInicio.required' => 'La fecha y hora de inicio es obligatoria',
            'dtRVirtualFin.required' => 'La fecha y hora de fin es obligatoria',
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
                'exec aula.SP_UPD_reunionVirtualesxiRVirtualId 
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
            'iRVirtualId.required' => 'No se encontró el identificador iRVirtualId'
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

    public function obtenerReunionVirtualesxiRVirtualId(Request $request, $iRVirtualId)
    {
        $request->merge(['iRVirtualId' => $iRVirtualId]);
        // Validación de los parámetros de entrada
        $validator = Validator::make($request->all(), [
            'iRVirtualId' => ['required'],
        ], [
            'iRVirtualId.required' => 'No se encontró el identificador iRVirtualId',
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
                $request->iRVirtualId      ??  NULL,
                $request->iCredId              ??  NULL
            ];

            $data = DB::select(
                'exec aula.SP_SEL_reunionVirtualesxiRVirtualId
                    @_iRVirtualId=?, 
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
