<?php

namespace App\Http\Controllers\api\seg;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CredSecurityCodeController extends Controller
{
    public function index(Request $request){

        $id = $request->id;
        $codigo = mt_rand(100000,999999);
        $session = 1;

        $upd_query = DB::select('Sp_UPD_cCredSecurityCode_credencialesXiCredId ?,?,?',[$id, $codigo, $session]);
        //$sel_query = DB::select('EXECUTE seg.Sp_SEL_credencialesXiCredId ?,?',[$id]);

        try{
            $response = [
                'validated' => true, 
                'message' => 'se obtuvo la información',
                'data' => $upd_query,
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
