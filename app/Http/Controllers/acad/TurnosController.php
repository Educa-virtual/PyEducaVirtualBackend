<?php

namespace App\Http\Controllers\acad;

use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TurnosController extends Controller
{
    public function obtenerTurnos(Request $request)
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
                'exec acad.SP_SEL_turnos
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
