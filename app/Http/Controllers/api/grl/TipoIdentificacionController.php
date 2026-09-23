<?php

namespace App\Http\Controllers\api\grl;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoIdentificacionController extends Controller
{
    public function list(Request $request)
    {
        $query = DB::select('SELECT * FROM grl.tipos_Identificaciones');
        try {
            $response = [
                'validated' => true,
                'message' => 'Se obtuvo la información',
                'data' => $query,
            ];
            $estado = 200;
        } catch (Exception $e) {
            $response = [
                'validated' => true,
                'message' => $e->getMessage(),
                'data' => [],
            ];
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }
}
