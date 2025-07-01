<?php

namespace App\Http\Controllers\enc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;

class TipoPublicoController extends Controller
{
    public function listarTipoPublico(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iTipPubId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCredId              ??  NULL
            ];

            $data = DB::select(
                'exec enc.SP_SEL_tipoPublico 
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
