<?php

namespace App\Http\Controllers\cap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;

class CapacitacionesController extends Controller
{
    //Notas: Campo iEstado
    // null => Obtendré todos los registros menos los eliminados
    // 0 => Eliminado
    // 1 => Activo
    // 2 => Publicado
    // 3 => En proceso
    // 4 => Finalizado
    public function guardarCapacitaciones(Request $request)
    {
        // return $request->all();
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
                $request->cHorario             ??  NULL,
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
                    @_cHorario=?,   
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
                $request->iEstado              ??  NULL,
                $request->iCredId              ??  NULL
            ];

            $data = DB::select(
                'exec cap.SP_SEL_capacitaciones 
                    @_iEstado=?,   
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

    public function actualizarCapacitaciones(Request $request)
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
                $request->cHorario             ??  NULL,
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
                    @_cHorario=?,   
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

    public function eliminarCapacitaciones(Request $request)
    {
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
}
