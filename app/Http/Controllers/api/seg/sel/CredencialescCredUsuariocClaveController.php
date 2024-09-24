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
        /*$sel_query = DB::select("EXECUTE SELECT 1 AS iResult,
		   c.iCredId,
		   c.iPersId,
		   c.cCredUsuario,
		   p.cPersDocumento,
		   CASE WHEN p.iTipoIdentId NOT IN (2,4) THEN COALESCE(p.cPersPaterno,'')+' '+COALESCE(p.cPersMaterno,'')+' '+COALESCE(p.cPersNombre,'') ELSE p.cPersRazonSocialNombre END AS cPersNombreLargo,
		   c.dtCredRegistro,
		   c.cCredToken,
		   c.iCredIntentos,
		   c.iCredSesionId,
		   con.contactar
	FROM seg.credenciales AS c
	LEFT OUTER JOIN grl.personas AS p ON c.iPersId=p.iPersId
	OUTER APPLY(select gcon.iPersConId,gcon.cPersConNombre
		from grl.personas_contactos as gcon
		   where gcon.iPersId=p.iPersId
		   for json path) as con(contactar)
	WHERE c.iCredId=?",[1]);
        */
        $conctactar = json_decode($sel_query[0]->contactar,true);
        $patron = "/^[[:digit:]]+$/";
        foreach($conctactar as $key => $correo){
            if (!preg_match($patron, $correo["cPersConNombre"])) {
                $separar = explode("@",$correo["cPersConNombre"]);
                $conctactar[$key]["iPersConId"] = bcrypt($correo["iPersConId"]);
                $conctactar[$key]["cPersConNombre"] = $separar[0][0].$separar[0][1]."******"."@".$separar[1];
            }
        }
        
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
