<?php

namespace App\Http\Controllers\cap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;
use App\Http\Controllers\grl\PersonasController;
use Illuminate\Support\Facades\DB;

class InstructoresController extends Controller
{   
    //Notas: Campo iEstado
    // 0 => Eliminado
    // 1 => Activo
    // 10 => Inactivo

    public function buscarInstructorxiTipoIdentIdxcPersDocumento($iTipoIdentId = 1, $cPersDocumento = null, Request $request)
    {

        try {

            $request->merge(['iTipoIdentId' => $iTipoIdentId]);
            $request->merge(['cPersDocumento' => $cPersDocumento]);

            $data = new PersonasController();
            $data = $data->buscarPersonaxiTipoIdentIdxcPersDocumento($request);

            if (isset($data['data']['iPersId'])) {
                return new JsonResponse(
                    ['validated' => false, 'message' => 'Se obtuvo la información exitosamente', 'data' => $data['data']],
                    Response::HTTP_OK
                );
            } else {
                return new JsonResponse(
                    ['validated' => false, 'message' => 'No se encontró información', 'data' => []],
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

    public function listarInstructores(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iInstId',
                'iPersId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iEstado              ??  NULL,
                $request->iCredId              ??  NULL
            ];

            $data = DB::select(
                'exec cap.SP_SEL_instructores 
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


}
