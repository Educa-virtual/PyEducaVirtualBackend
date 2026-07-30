<?php

namespace App\Http\Controllers\cap;

use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CapacitacionesController extends Controller
{
    // Notas: Campo iEstado
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
                'iTipoModalId',
            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iTipoCapId ?? null,
                $request->iNivelPedId ?? null,
                $request->iTipoPubId ?? null,
                $request->cCapTitulo ?? null,
                $request->cCapDescripcion ?? null,
                $request->iTotalHrs ?? null,
                $request->dFechaInicio ?? null,
                $request->dFechaFin ?? null,
                $request->iInstId ?? null,
                $request->iCosto ?? null,
                $request->nCosto ?? null,
                $request->iImageAleatorio ?? null,
                $request->cImagenUrl ?? null,
                $request->cLink ?? null,
                $request->iCredId ?? null,

                $request->jsonHorario ?? null,

                $request->iTipoModalId ?? null,
                $request->nNotaMinimo ?? null,
                $request->iTotalCupo ?? null,
                $request->bMostrarTemario ?? null,

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
                    @_jsonHorario=?,
                    @_iTipoModalId=?,
                    @_nNotaMinimo=?,
                    @_iTotalCupo=?,
                    @_bMostrarTemario=?
                    ',
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
                'iTipoModalId',
            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCredId ?? null,
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
                'iTipoModalId',
            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCapacitacionId ?? null,
                $request->iTipoCapId ?? null,
                $request->iNivelPedId ?? null,
                $request->iTipoPubId ?? null,
                $request->cCapTitulo ?? null,
                $request->cCapDescripcion ?? null,
                $request->iTotalHrs ?? null,
                $request->dFechaInicio ?? null,
                $request->dFechaFin ?? null,
                $request->iInstId ?? null,
                $request->iCosto ?? null,
                $request->nCosto ?? null,
                $request->iImageAleatorio ?? null,
                $request->cImagenUrl ?? null,
                $request->cLink ?? null,
                $request->iCredId ?? null,

                $request->jsonHorario ?? null,

                $request->iTipoModalId ?? null,
                $request->nNotaMinimo ?? null,
                $request->iTotalCupo ?? null,
                $request->bMostrarTemario ?? null,

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
                    @_jsonHorario=?,  
                    @_iTipoModalId=?,
                    @_nNotaMinimo=?,
                    @_iTotalCupo=?,
                    @_bMostrarTemario=?
                    ',
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
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);
            $parametros = [
                $request->iCapacitacionId ?? null,
                $request->iCredId ?? null,
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
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $iEstado = $request->bEstado ? 10 : 2;
        $request->merge(['iEstado' => $iEstado]);

        try {
            $fieldsToDecode = [
                'iCapacitacionId',
                'iCredId',
            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);
            $parametros = [
                $request->iCapacitacionId ?? null,
                $request->iEstado ?? null,
                $request->iCredId ?? null,
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
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCredId ?? null,
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
                'iInstId',
            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

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
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iCredId',
                'iCapacitacionId',
            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->cPerfil ?? null,
                $request->iCredId ?? null,
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

    public function listarCapacitacionesPublicadasxiCapacitacionId(Request $request, $iCapacitacionId)
    {

        $request->merge(['iCapacitacionId' => $iCapacitacionId]);
        try {
            $fieldsToDecode = [
                'iCapacitacionId',
                'iTipoCapId',
                'iNivelPedId',
                'iTipoPubId',
                'iInstId',
            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCapacitacionId ?? null
            ];

            $data = DB::select(
                'exec cap.SP_SEL_capacitacionxiCapacitacionId 
                @_iCapacitacionId=?',
                $parametros
            );


            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);

            if (count($data) == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Capacitación no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $data[0]
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
