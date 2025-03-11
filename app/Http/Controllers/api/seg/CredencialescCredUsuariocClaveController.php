<?php

namespace App\Http\Controllers\api\seg;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AuditoriaAccesos;
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

        $this->middleware(AuditoriaAccesos::class)->only(['login']);
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
            return new JsonResponse(['validated' => false, 'message' => $validator->errors(), 'data' => []]);
        }

        $credentials = ['cCredUsuario' => $request->user, 'password' => $request->pass];

        $intentos = DB::select('select iCredIntentos from seg.credenciales where cCredUsuario = ?', [$credentials['cCredUsuario']]);
        $duracion =  DB::select('select DATEDIFF(minute, dtCredRegistro, GETDATE()) as duracion from seg.credenciales where cCredUsuario = ?', [$credentials['cCredUsuario']]);

         if (count($intentos)>0 && count($duracion) > 0) {
            if (!$user = $this->customAttempt($credentials)) {
                if ((int)$intentos[0]->iCredIntentos === 3 && (int)$duracion[0]->duracion < 5) {
                    return response()->json(['validated' => false, 'message' => 'Ya alcanzó el límite de intentos, vuelva a intentar en 5 minutos.'], 401);
                } else {
                    if ((int)$duracion[0]->duracion >= 5 || (int)$intentos[0]->iCredIntentos >= 5) {
                        return response()->json(['validated' => false, 'message' => 'Ya alcanzó el límite de intentos, comuníquese con el administrador'], 401);
                    }
                    DB::update('update seg.credenciales set iCredIntentos =  (iCredIntentos + 1), dtCredRegistro = GETDATE() where cCredUsuario = ?', [$credentials['cCredUsuario']]);
                }
            }
        }
        if ($user = $this->customAttempt($credentials) && (int)$duracion[0]->duracion >= 5 && (int)$intentos[0]->iCredIntentos >= 5) {
            return response()->json(['validated' => false, 'message' => 'Ya alcanzó el límite de intentos, comuníquese con el administrador'], 401);
        }
        $user = $request->user;
        $pass = $request->pass;
        $data = DB::select('EXECUTE seg.Sp_SEL_credencialesXcCredUsuarioXcClave ?,?', [$user, $pass]);

        if (count($data) == 0) {
            return response()->json(['validated' => false, 'message' => 'El usuario no existe en nuestros registros.', 'data' => []], 403);
        }

        if (!$user = $this->customAttempt($credentials)) {
            return response()->json(['validated' => false, 'message' => 'Verifica tu usuario y contraseña'], 401);
        } else {
            $vencimiento = DB::select("
                SELECT
                    DATEDIFF(DAY, c.dtCredRegistro,GETDATE() ) AS iDias
                FROM seg.credenciales AS c
                WHERE c.cCredUsuario = '" . $credentials['cCredUsuario'] . "'
            ");
            if ($vencimiento[0]->iDias <= 60 && $vencimiento[0]->iDias >= 0) {
                DB::update('update seg.credenciales set iCredIntentos =  0, dtCredRegistro = GETDATE() where cCredUsuario = ?', [$credentials['cCredUsuario']]);
            } else {
                return response()->json(['validated' => false, 'message' => 'Debe de actualizar su contraseña'], 401);
            }
        }

        $token = JWTAuth::fromUser($user);
        //Obtener roles 
        $perfiles = DB::select('EXEC seg.Sp_SEL_credenciales_entidades_perfilesXiCredId ?', [$data[0]->iCredId]);
        $data[0]->perfiles = $perfiles;
        if ($data[0]->contactar) {
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
        }
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
                            SELECT  y.iYearId    ,y.cYearNombre       ,y.cYearOficial  ,
(select top(1) iYAcadId from acad.year_academicos WHERE iYearId= y.iYearId) as iYAcadId
 FROM grl.years as y ORDER BY y.cYearNombre DESC
                            ");

        $user->modulos = $modulos;
        $user->years = $years;
        $user->iDocenteId = $this->hashids->encode($user->iDocenteId);
        $user->iPersId = $this->hashids->encode($user->iPersId);
        return response()->json([
            'accessToken' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => $user

        ]);
    }
}
