<?php

namespace App\Http\Controllers\cap;

use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TipoModalidadController extends Controller
{
    public function listarTipoModalidad()
    {
        try {
            $fieldsToDecode = [
                'iTipoModalId',
            ];

            $data = DB::select(
                'exec cap.SP_SEL_tipoModalidad',
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
