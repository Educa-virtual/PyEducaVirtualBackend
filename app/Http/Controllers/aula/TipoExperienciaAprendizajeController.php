<?php

namespace App\Http\Controllers\aula;

use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TipoExperienciaAprendizajeController extends Controller
{
    public function listarTipoExperienciaAprendizaje(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iCredId',
            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCredId ?? null,
            ];

            $data = DB::select(
                'exec aula.SP_SEL_tipoExperienciaAprendizaje
                    @_iCredId=?',
                $parametros
            );

            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);

            return new JsonResponse(
                ['validated' => true, 'message' => 'Se ha obtenido exitosamente ', 'data' => $data],
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
