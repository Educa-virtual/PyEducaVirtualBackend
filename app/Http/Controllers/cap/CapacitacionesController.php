<?php

namespace App\Http\Controllers\cap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CapacitacionesController extends Controller
{
    //Notas: Campo iEstado
    // null => Obtendré todos los registros menos los eliminados
    // 0 => Eliminado
    // 1 => Activo
    // 2 => Publicado
    // 10 => Finalizado
    public function guardarCapacitaciones(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iTipoCapId',
                'iNivelPedId',
                'iTipoPubId',
                'iInstId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iTipoCapId           ??  NULL,
                $request->iNivelPedId          ??  NULL,
                $request->iTipoPubId           ??  NULL,
                $request->cCapTitulo           ??  NULL,
                $request->cCapDescripcion      ??  NULL,
                $request->iTotalHrs            ??  NULL,
                $request->dFechaInicio         ??  NULL,
                $request->dFechaFin            ??  NULL,
                $request->iInstId              ??  NULL,
                $request->iCosto               ??  NULL,
                $request->nCosto               ??  NULL,
                $request->iImageAleatorio      ??  NULL,
                $request->cImagenUrl           ??  NULL,
                $request->cLink                ??  NULL,
                $request->iCredId              ??  NULL,

                $request->jsonHorario          ??  NULL

            ];

            $data = DB::select(
                'exec cap.SP_INS_capacitaciones
                    @_iTipoCapId=?,
                    @_iNivelPedId=?,
                    @_iTipoPubId=?,
                    @_cCapTitulo=?,
                    @_cCapDescripcion=?,
                    @_iTotalHrs=?,
                    @_dFechaInicio=?,
                    @_dFechaFin=?,
                    @_iInstId=?,
                    @_iCosto=?,
                    @_nCosto=?,
                    @_iImageAleatorio=?,
                    @_cImagenUrl=?,
                    @_cLink=?,
                    @_iCredId=?,
                    @_jsonHorario=?',
                $parametros
            );

            if ($data[0]->iCapacitacionId > 0) {
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

    public function listarCapacitaciones(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iCapacitacionId',
                'iTipoCapId',
                'iNivelPedId',
                'iTipoPubId',
                'iInstId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCredId              ??  NULL
            ];

            $data = DB::select(
                'exec cap.SP_SEL_capacitaciones  
                    @_iCredId=?',
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

    public function actualizarCapacitaciones(Request $request, $iCapacitacionId)
    {
        $request->merge(['iCapacitacionId' => $iCapacitacionId]);

        try {
            $fieldsToDecode = [
                'iCapacitacionId',
                'iTipoCapId',
                'iNivelPedId',
                'iTipoPubId',
                'iInstId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCapacitacionId      ??  NULL,
                $request->iTipoCapId           ??  NULL,
                $request->iNivelPedId          ??  NULL,
                $request->iTipoPubId           ??  NULL,
                $request->cCapTitulo           ??  NULL,
                $request->cCapDescripcion      ??  NULL,
                $request->iTotalHrs            ??  NULL,
                $request->dFechaInicio         ??  NULL,
                $request->dFechaFin            ??  NULL,
                $request->iInstId              ??  NULL,
                $request->iCosto               ??  NULL,
                $request->nCosto               ??  NULL,
                $request->iImageAleatorio      ??  NULL,
                $request->cImagenUrl           ??  NULL,
                $request->cLink                ??  NULL,
                $request->iCredId              ??  NULL,

                $request->jsonHorario          ??  NULL

            ];

            $data = DB::select(
                'exec cap.SP_UPD_capacitaciones
                    @_iCapacitacionId=?,
                    @_iTipoCapId=?,
                    @_iNivelPedId=?,
                    @_iTipoPubId=?,
                    @_cCapTitulo=?,
                    @_cCapDescripcion=?,
                    @_iTotalHrs=?,
                    @_dFechaInicio=?,
                    @_dFechaFin=?,
                    @_iInstId=?,
                    @_iCosto=?,
                    @_nCosto=?,
                    @_iImageAleatorio=?,
                    @_cImagenUrl=?, 
                    @_cLink=?, 
                    @_iCredId=?,
                    @_jsonHorario=?',
                $parametros
            );

            if ($data[0]->iCapacitacionId > 0) {
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

    public function eliminarCapacitaciones(Request $request, $iCapacitacionId)
    {
        $request->merge(['iCapacitacionId' => $iCapacitacionId]);

        try {
            $fieldsToDecode = [
                'iCapacitacionId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);
            $parametros = [
                $request->iCapacitacionId             ??  NULL,
                $request->iCredId                     ??  NULL
            ];

            $data = DB::select(
                'exec cap.SP_DEL_capacitaciones 
                    @_iCapacitacionId=?, 
                    @_iCredId=?',
                $parametros
            );
            if ($data[0]->iCapacitacionId > 0) {
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

    public function actualizarEstadoCapacitacion(Request $request, $iCapacitacionId)
    {
        $request->merge(['iCapacitacionId' => $iCapacitacionId]);

        $validator = Validator::make($request->all(), [
            'iCapacitacionId' => ['required'],
            'bEstado' => ['required'],
        ], [
            'iCapacitacionId.required' => 'No se encontró el identificador iCapacitacionId',
            'bEstado.required' => 'No se encontró el estado',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $iEstado = $request->bEstado ? 10 : 2;
        $request->merge(['iEstado' => $iEstado]);

        try {
            $fieldsToDecode = [
                'iCapacitacionId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);
            $parametros = [
                $request->iCapacitacionId             ??  NULL,
                $request->iEstado                     ??  NULL,
                $request->iCredId                     ??  NULL
            ];

            $data = DB::select(
                'exec cap.SP_UPD_capacitacionesxiCapacitacionIdxiEstado 
                    @_iCapacitacionId=?, 
                    @_iEstado=?, 
                    @_iCredId=?',
                $parametros
            );

            $cEstado = $request->bEstado ? 'Finalizado' : 'Publicado';

            if ($data[0]->iCapacitacionId > 0) {
                $message = 'Se ha ' . $cEstado . ' correctamente la capacitación';
                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => null],
                    Response::HTTP_OK
                );
            } else {
                $message = 'No se ha ' . $cEstado . ' correctamente la capacitación';
                return new JsonResponse(
                    ['validated' => false, 'message' => $message, 'data' => null],
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

    public function listarCapacitacionesxMatriculados(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iCapacitacionId',
                'iTipoCapId',
                'iNivelPedId',
                'iTipoPubId',
                'iInstId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCredId              ??  NULL
            ];

            $data = DB::select(
                'exec cap.SP_SEL_capacitacionesxMatriculados 
                    @_iCredId=?',
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

    public function listarCapacitacionesPublicadas(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iCapacitacionId',
                'iTipoCapId',
                'iNivelPedId',
                'iTipoPubId',
                'iInstId'
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $data = DB::select(
                'exec cap.SP_SEL_capacitacionesPublicadas'
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

    public function listarCapacitacionesxiCredId(Request $request, $cPerfil, $iCredId)
    {
        $request->merge(['cPerfil' => $cPerfil]);
        $request->merge(['iCredId' => $iCredId]);

        $validator = Validator::make($request->all(), [
            'cPerfil' => ['required'],
            'iCredId' => ['required'],
        ], [
            'cPerfil.required' => 'No se encontró el perfil',
            'iCredId.required' => 'No se encontró la credencial',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->cPerfil              ??  NULL,
                $request->iCredId              ??  NULL
            ];

            $data = DB::select(
                'exec cap.SP_SEL_capacitacionesxcPerfilxiCredId 
                @_cPerfil=?,
                @_iCredId=?',
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
}
