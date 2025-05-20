<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\VerifyHash;
use App\Http\Requests\seg\LoginUsuarioRequest;
use App\Models\seg\Credencial;
use Exception;
use Hashids\Hashids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = "seg.credenciales";
    protected $primaryKey = "iCredId";
    public $timestamps = false;
    protected $fillable = [
        'cCredUsuario',
        'password',
    ];

    protected $hidden = [
        'password',
        'cCredToken',
        'cCredCodigoVerif',
        'cCredTokenPassword'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function validarPersonaCredencialPerfil($iCredEntPerfId, array $cPerfilesPermitidos)
    {
        $arrayPerfiles = [];
        foreach ($cPerfilesPermitidos as $perfil) {
            $arrayPerfiles[] = $perfil->value;
        }
        try {
            DB::statement("EXEC [seg].[Sp_SEL_validarPersonaCredencialPerfil] @_iPersId=?, @_iCredEntPerfId=?,
            @_cPerfilesPermitidos=?", [$this->iPersId, $iCredEntPerfId, implode(',', $arrayPerfiles)]);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public static function login(LoginUsuarioRequest $request)
    {
        $usuario = DB::selectOne('EXECUTE seg.Sp_SEL_credencialesXcCredUsuarioXcClave ?,?', [$request->user, $request->pass]);
        self::codificarContactos($usuario);
        self::obtenerOtrosDatos($usuario);
        self::encriptarIds($usuario);
        return [
            'accessToken' => self::generarToken($usuario),
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * (int)env('JWT_TTL', 60),
            'user' => $usuario
        ];
    }

    private static function generarToken($usuario)
    {
        $usuarioParaJwt = User::find($usuario->iCredId); //where('iCredId', $usuario->iCredId)->first();
        return JWTAuth::fromUser($usuarioParaJwt);
    }

    private static function encriptarIds($usuario)
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        //$hashids->encode($usuario->iCredId);
        $usuario->iDocenteId =$hashids->encode($usuario->iDocenteId);//VerifyHash::encode($usuario->iDocenteId);
        $usuario->iPersId = $hashids->encode($usuario->iDocenteId);//$hashids->encode($usuario->iPersId)[0];//VerifyHash::encode($usuario->iPersId);
    }

    private static function obtenerOtrosDatos($usuario)
    {
        $usuario->perfiles = DB::select('EXEC seg.Sp_SEL_credenciales_entidades_perfilesXiCredId ?', [$usuario->iCredId]);
        $usuario->modulos = DB::select("SELECT iModuloId, cModuloNombre FROM seg.modulos WHERE iModuloEstado = 1 ORDER BY iModuloOrden ASC");
        $usuario->years = DB::select("SELECT  y.iYearId    ,y.cYearNombre       ,y.cYearOficial  ,
        (select top(1) iYAcadId from acad.year_academicos WHERE iYearId= y.iYearId) as iYAcadId
        FROM grl.years as y ORDER BY y.cYearNombre DESC");
    }

    private static function codificarContactos($usuario)
    {
        if ($usuario->contactar) {
            $conctactar = json_decode($usuario->contactar, true);
            $patron = "/^[[:digit:]]+$/";
            foreach ($conctactar as $key => $correo) {
                if (!preg_match($patron, $correo["cPersConNombre"])) {
                    $separar = explode("@", $correo["cPersConNombre"]);
                    $conctactar[$key]["iPersConId"] = bcrypt($correo["iPersConId"]);
                    $conctactar[$key]["cPersConNombre"] = $separar[0][0] . $separar[0][1] . "******" . "@" . $separar[1];
                }
            }
            $usuario->contactar = $conctactar;
        }
    }
}
