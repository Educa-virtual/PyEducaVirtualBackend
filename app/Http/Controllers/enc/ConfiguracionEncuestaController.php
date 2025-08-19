<?php

namespace App\Http\Controllers\enc;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use App\Http\Requests\enc\RegistrarConfiguracionEncuestaRequest;
use App\Services\enc\ConfiguracionEncuestasService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class ConfiguracionEncuestaController extends Controller
{
    public function registrarConfiguracion(RegistrarConfiguracionEncuestaRequest $request)
    {
        try {
            $resultado = ConfiguracionEncuestasService::registrarConfiguracion($request, Auth::user());
            return FormatearMensajeHelper::ok('Se ha registrado la configuración', $resultado);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
    /*public function listarConfiguracionEncuesta(Request $request, $iConfEncId = 0)
    {
        try {
            $fieldsToDecode = [
                'iConfEncId',
                'iCredId',
            ];
            $request->merge(['iConfEncId' => $iConfEncId]);
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iConfEncId           ??  0,
                $request->iCredId              ??  NULL
            ];

            $data = DB::select(
                'exec enc.SP_SEL_configuracionEncuesta
                    @_iConfEncId=?,
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

    public function eliminarConfiguracionEncuesta(Request $request, $iConfEncId)
    {
        try {
            $fieldsToDecode = [
                'iConfEncId',
                'iTiemDurId',
                'iCredId',
            ];
            $request->merge(['iConfEncId' => $iConfEncId]);
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iConfEncId           ??  NULL,
                $request->iCredId              ??  NULL
            ];

            $data = DB::select(
                'exec enc.SP_DEL_configuracionEncuestaxiConfEncId
                    @_iConfEncId=?,
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iConfEncId > 0) {
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

    public function guardarConfiguracionEncuesta(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iConfEncId',
                'iTiemDurId',
                'iCredId',
            ];

            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);
            // FALTA DESENCODIFICAR LOS CAMPOS QUE VIENEN EN EL JSON
            $parametros = [
                $request->cConfEncNombre            ??  NULL,
                $request->cConfEncSubNombre         ??  NULL,
                $request->dConfEncInicio            ??  NULL,
                $request->dConfEncFin               ??  NULL,
                $request->iTiemDurId                ??  NULL,
                $request->cConfEncDesc              ??  NULL,
                $request->cDirigido                 ??  NULL, //Formato JSON
                $request->jsonPublicoObjetivo       ??  NULL,
                $request->iCredId                   ??  NULL
            ];

            $data = DB::select(
                'exec enc.SP_INS_configuracionEncuesta
                    @_cConfEncNombre=?,
                    @_cConfEncSubNombre=?,
                    @_dConfEncInicio=?,
                    @_dConfEncFin=?,
                    @_iTiemDurId=?,
                    @_cConfEncDesc=?,
                    @_cDirigido=?,
                    @_jsonPublicoObjetivo=?,
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iConfEncId > 0) {
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
    }*/
}
