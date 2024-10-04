<?php

namespace App\Http\Controllers\api\seg;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CredencialModuloController extends Controller
{
    public function list(Request $request){
        
        $iCredEntId = $request->iCredEntId;
        $query = DB::select('seg.Sp_SEL_accesos_modulos_padresXiCredEntId ?',[$iCredEntId]);

        try{
            $response = [
                'validated' => true, 
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 200;

        }catch(Exception $e){
            $response = [
                'validated' => true, 
                'message' => $e->getMessage(),
                'data' => [],
            ];

            $estado = 500;
        }

        return new JsonResponse($response,$estado);
    }
}
