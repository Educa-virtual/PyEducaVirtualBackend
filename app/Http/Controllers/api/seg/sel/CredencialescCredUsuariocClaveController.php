<?php

namespace App\Http\Controllers\api\seg\sel;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CredencialescCredUsuariocClaveController extends Controller
{
    public function login(Request $request){
        $user = $request->user;
        $pass = $request->pass;

        $sel_query = DB::select('EXECUTE seg.Sp_SEL_credencialesXcCredUsuarioXcClave ?,?',[$user,$pass]);
    
        try{
            $response = [
                'validated' => true, 
                'message' => 'se obtuvo la información',
                'data' => $sel_query,
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
