<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\seg\Credencial;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;

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
        $arrayPerfiles=[];
        foreach ($cPerfilesPermitidos as $perfil) {
            $arrayPerfiles[] = $perfil->value;
        }
        try {
            $myfile = fopen("D:\\test.txt", "w") or die("Unable to open file!");
$txt = $this->iPersId.' - '.$iCredEntPerfId.' - '.implode(',', $arrayPerfiles);
fwrite($myfile, $txt);
fclose($myfile);
            DB::statement("EXEC [seg].[Sp_SEL_validarPersonaCredencialPerfil] @_iPersId=?, @_iCredEntPerfId=?,
            @_cPerfilesPermitidos=?", [$this->iPersId, $iCredEntPerfId, implode(',', $arrayPerfiles)]);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
}
