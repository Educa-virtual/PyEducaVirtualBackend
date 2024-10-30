<?php

namespace App\Http\Controllers\api\seg;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTException;
use App\Models\User;
use Hashids\Hashids;

class CredencialescCredUsuariocClaveController extends Controller
{   
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function customAttempt($credentials)
    {
        $user = User::where('cCredUsuario', $credentials['cCredUsuario'])->first();

        if ($user && sha1($credentials['password']) === $user->password) {
            return $user;
        }

        return false;
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user' => 'required',
            'pass' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['validated' => false, 'message' => $validator->errors(), 'data' => []], 422);
        }

        $credentials = ['cCredUsuario' => $request->user, 'password' => $request->pass];

        if (!$user = $this->customAttempt($credentials)) {
            return response()->json(['validated' => false, 'error' => 'Verifica tu usuario y contraseña'], 401);
        }
        $token = JWTAuth::fromUser($user);


        $user = $request->user;
        $pass = $request->pass;
        $data = DB::select('EXECUTE seg.Sp_SEL_credencialesXcCredUsuarioXcClave ?,?', [$user, $pass]);

        if (count($data) == 0) {
            return response()->json(['validated' => false, 'message' => 'El usuario no existe en nuestros registros.', 'data' => []], 403);
        }

        //Obtener roles 
        $perfiles = DB::select('EXEC seg.Sp_SEL_credenciales_entidades_perfilesXiCredEntId ?', [$data[0]->iCredId]);
        $data[0]->perfiles = $perfiles;

        $conctactar = json_decode($data[0]->contactar, true);
        $patron = "/^[[:digit:]]+$/";
        foreach ($conctactar as $key => $correo) {
            if (!preg_match($patron, $correo["cPersConNombre"])) {
                $separar = explode("@", $correo["cPersConNombre"]);
                $conctactar[$key]["iPersConId"] = bcrypt($correo["iPersConId"]);
                $conctactar[$key]["cPersConNombre"] = $separar[0][0] . $separar[0][1] . "******" . "@" . $separar[1];
            }
        }

        $data[0]->contactar = $conctactar;

        return $this->createNewToken($token, $data);
    }

    protected function createNewToken($token, $data)
    {
        $user = count($data) > 0 ? $data[0] : [];
        $modulos = DB::select("
                            SELECT 
                               iModuloId
							  ,cModuloNombre
															
							FROM seg.modulos
							WHERE iModuloEstado = 1
														
							ORDER BY iModuloOrden ASC
                            ");
        $years = DB::select("
                            SELECT 
                             iYearId
                            ,cYearNombre
                            ,cYearOficial
                                                            
                            FROM grl.years
                           
                                                        
                            ORDER BY cYearNombre DESC
                            ");

        $user->modulos = $modulos;
        $user->years = $years;
        $user->iDocenteId = $this->hashids->encode($user->iDocenteId);

        return response()->json([
            'accessToken' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => $user

        ]);
    }
}
