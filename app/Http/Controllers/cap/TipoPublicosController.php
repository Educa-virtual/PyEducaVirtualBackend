<?php

namespace App\Http\Controllers\cap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;

class TipoPublicosController extends Controller
{
    public function listarTipoPublicos()
    {
        try {
            $fieldsToDecode = [
                'iTipoPubId',
            ];

            $data = DB::select(
                'exec cap.SP_SEL_tipoPublicos',
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

    public function guardarTipoPublicos(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iTipoPubId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->cTipoPubNombre       ??  NULL,

                $request->iCredId              ??  NULL
            ];

            $data = DB::select(
                'exec cap.SP_INS_tipoPublicos
                    @_cTipoPubNombre=?,
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iTipoPubId > 0) {
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

    public function actualizarTipoPublicos($iTipoPubId, Request $request)
    {
        $request->merge(['iTipoPubId' => $iTipoPubId]);

        try {
            $fieldsToDecode = [
                'iTipoPubId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iTipoPubId           ??  NULL,
                $request->cTipoPubNombre       ??  NULL,

                $request->iCredId              ??  NULL
            ];

            $data = DB::select(
                'exec cap.SP_UPD_tipoPublicos
                    @_iTipoPubId=?,
                    @_cTipoPubNombre=?,
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iTipoPubId > 0) {
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

    public function eliminarTipoPublicos($iTipoPubId, Request $request)
    {
        $request->merge(['iTipoPubId' => $iTipoPubId]);

        try {
            $fieldsToDecode = [
                'iTipoPubId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iTipoPubId           ??  NULL,

                $request->iCredId              ??  NULL
            ];

            $data = DB::select(
                'exec cap.SP_DEL_tipoPublicos
                    @_iTipoPubId=?,
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iTipoPubId > 0) {
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
