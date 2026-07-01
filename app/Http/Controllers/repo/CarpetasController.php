<?php

namespace App\Http\Controllers\repo;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use App\Http\Requests\repo\ActualizarCarpetaRequest;
use App\Http\Requests\repo\GuardarCarpetaRequest;
use Illuminate\Http\Response;
use App\Models\repo\Carpeta;

class CarpetasController extends Controller
{
    public function listarCarpetas(Request $request)
    {
        try {
            $data = Carpeta::selCarpetas($request);
            return FormatearMensajeHelper::ok('Se ha obtenido exitosamente ', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verCarpeta(Request $request)
    {
        try {
            $data = Carpeta::selCarpeta($request);
            return FormatearMensajeHelper::ok('Se ha obtenido exitosamente ', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarCarpeta(ActualizarCarpetaRequest $request)
    {
        try {
            $data = Carpeta::updCarpeta($request);
            if ($data->iCarpetaId > 0) {
                return FormatearMensajeHelper::ok('Se ha actualizado exitosamente ', $data);
            } else {
                throw new \Exception('No se ha podido actualizar', 500);
            }
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarCarpeta(GuardarCarpetaRequest $request)
    {
        try {
            $data = Carpeta::insCarpeta($request);
            if ($data->iCarpetaId > 0) {
                return FormatearMensajeHelper::ok('Se ha guardado exitosamente ', $data);
            } else {
                throw new \Exception('No se ha podido guardar', 500);
            }
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
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
