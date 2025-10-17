<?php

namespace App\Http\Controllers\repo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CarpetasController extends Controller
{
    public function guardarCarpeta(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iCarpetaId',
                'iPersId',
                'iParentCarpetaId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->cNombre           ??  NULL,
                $request->iPersId           ??  NULL,
                $request->iParentCarpetaId  ??  NULL,

                $request->iCredId           ??  NULL

            ];

            $data = DB::select(
                'exec repo.SP_INS_carpetas
                    @_cNombre=?,
                    @_iPersId=?,
                    @_iParentCarpetaId=?,
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iCarpetaId > 0) {
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

    public function obtenerCarpetas(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iCarpetaId',
                'iPersId',
                'iParentCarpetaId',
                'iCredId',
                'iId', //iCarpetaId o iArchivoId
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCarpetaId           ??  NULL,
                $request->iPersId              ??  NULL,
                $request->iCredId              ??  NULL,
            ];

            $data = DB::select(
                'exec repo.SP_SEL_carpetas
                 @_iCarpetaId=?,
                 @_iPersId=?,
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

    public function actualizarCarpeta(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iCarpetaId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCarpetaId        ??  NULL,
                $request->cNombre           ??  NULL,

                $request->iCredId           ??  NULL

            ];

            $data = DB::select(
                'exec repo.SP_UPD_carpetas
                    @_iCarpetaId=?,
                    @_cNombre=?,
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iCarpetaId > 0) {
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

    public function eliminarCarpeta(Request $request)
    {

        try {
            $fieldsToDecode = [
                'iCarpetaId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCarpetaId        ??  NULL,

                $request->iCredId           ??  NULL

            ];

            $data = DB::select(
                'exec repo.SP_DEL_carpetas
                    @_iCarpetaId=?,
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iCarpetaId > 0) {
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
