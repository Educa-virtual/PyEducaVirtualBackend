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

class CredencialescCredUsuariocClaveController extends Controller
{
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


        $data = DB::select("
            SELECT 
                c.iCredId
                    -- ENTIDADES
                ,(
                    SELECT
                            e.cEntNombreLargo -- FALTA OBTENER COD MODULAR - NIVEL - UGEL
                                        FROM seg.credenciales_entidades AS ce
                            INNER JOIN grl.entidades AS e ON e.iEntId = ce.iEntId
                            where ce.iCredEntId = c.iCredId

                        FOR JSON PATH, INCLUDE_NULL_VALUES
                    ) AS entidades
                    -- AÑOS
                    ,(
                            SELECT 
                            cYAcadNombre
                            FROM acad.year_academicos
                            
                            FOR JSON PATH, INCLUDE_NULL_VALUES
                        ) AS years
                    -- PERFILES
                    ,(
                    SELECT
                                p.iPerfilId
                        ,p.cPerfilNombre
                                        FROM seg.credenciales_entidades_perfiles AS cep
                            INNER JOIN seg.credenciales_entidades AS sce ON sce.iCredEntId = cep.iCredEntId
                                        INNER JOIN seg.perfiles AS p ON p.iPerfilId = cep.iPerfilId
                            WHERE sce.iCredEntId = c.iCredId
                                        
                                        ORDER BY p.iPerfilOrden ASC

                        FOR JSON PATH, INCLUDE_NULL_VALUES
                    ) AS perfiles

                FROM seg.credenciales as c

                WHERE c.iCredId = " . $user->iCredId . "
        ");

        $entidades = count($data) > 0 ? $data[0]->entidades : [];
        $perfiles = count($data) > 0 ? $data[0]->perfiles : [];
        $years = count($data) > 0 ? $data[0]->years : [];


        return response()->json([
            'accessToken' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => $user,
            'modulos' => [],
            'years' => $years,
            'entidades' => $entidades,
            'perfiles' => $perfiles,

        ]);
    }
}
