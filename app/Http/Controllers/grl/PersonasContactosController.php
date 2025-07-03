<?php

namespace App\Http\Controllers\grl;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PersonasContactosController extends Controller
{
    public function enviarCodVerificarCorreo(Request $request)
    {
        $request->validate(
            [
                'iPersId' => 'required',
                'cPersCorreo' => 'required',
            ],
            [
                'iPersId.required' => 'Hubo un problema al obtener iPersId',
                'cPersCorreo.required' => 'Hubo un problema al obtener cPersCorreo',
            ]
        );
        $request['cPersConCodigoValidacion'] = mt_rand(100000, 999999);

        $parametros = [
            $request->iPersId,
            $request->cPersCorreo,
            $request->iTipoConId,
            $request->cPersConCodigoValidacion

        ];
        try {
            $data = DB::select("execute grl.Sp_UPD_personasContactosxcPersConCodigoValidacion ?,?,?,?", $parametros);
            
            if ($data[0]->iPersConId > 0) {
                $request['iPersConId'] = $data[0]->iPersConId;
                $resp = new MailController();
                return $resp->enviarMailCodVerificarCorreo($request);

                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function verificarCodVerificarCorreo(Request $request){

        $parametros = [
            $request->cCodeVerif,
            $request->iPersConId
        ];
        try {
            $data = DB::select("execute grl.Sp_UPD_verificarPersonasContactosxcPersConCodigoValidacion ?,?", $parametros);
           
            if ($data[0]->iPersConId > 0) {
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);

    }

    public function obtenerxiPersId(Request $request){

        $parametros = [
            $request->iPersId,
            $request->iCredId
        ];
        try {
            $data = DB::select(
                'exec grl.SP_SEL_personasContactosxiPersId 
                    @_iPersId=?,   
                    @_iCredId=?',
                $parametros
            );
           
            if (count($data) > 0) {
                return new JsonResponse(
                    ['validated' => true, 'message' => 'Se ha obtenido exitosamente ', 'data' => $data[0]],
                    Response::HTTP_OK
                );
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido obtener la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);

    }
}
