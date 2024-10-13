<?php

namespace App\Http\Controllers\api\seg;

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
        $conctactar = json_decode($sel_query[0]->contactar,true);
        $patron = "/^[[:digit:]]+$/";
        foreach($conctactar as $key => $correo){
            if (!preg_match($patron, $correo["cPersConNombre"])) {
                $separar = explode("@",$correo["cPersConNombre"]);
                $conctactar[$key]["iPersConId"] = bcrypt($correo["iPersConId"]);
                $conctactar[$key]["cPersConNombre"] = $separar[0][0].$separar[0][1]."******"."@".$separar[1];
            }
        }

        $perfiles = DB ::select('EXEC seg.Sp_SEL_credenciales_entidades_perfilesXiCredEntId ?', [$sel_query[0]->iCredId]);
        $sel_query[0]->perfiles = $perfiles;
        
        $sel_query[0]->contactar = $conctactar;

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
