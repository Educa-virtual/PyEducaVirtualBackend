<?php

namespace App\Http\Controllers\aula;

use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TipoPreguntasController extends Controller
{
    public function listarTipoPreguntas(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iTipoPregId',
                'iCredId',
            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCredId ?? null,
            ];

            $data = DB::select(
                'exec aula.SP_SEL_tipoPreguntas 
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
