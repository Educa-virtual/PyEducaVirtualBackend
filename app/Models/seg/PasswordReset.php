<?php

namespace App\Models\seg;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordReset extends Model
{

    public static function insToken($iCredId, $cCodigoHash)
    {
        DB::statement("INSERT INTO [seg].[password_resets]
           ([iCredId]
           ,[cCodigoHash]
           ,[dtFechaExpiracion])
            VALUES
           (?,?,DATEADD(MINUTE, 10, GETDATE()))", [$iCredId, $cCodigoHash]);
    }

    public static function updAnularTokensUsuario($iCredId)
    {
        DB::statement("UPDATE [seg].[password_resets] SET bUtilizado=1 WHERE iCredId=?", [$iCredId]);
    }

    public static function selUltimoTokenValidacion($iCredId)
    {
        return DB::selectOne("SELECT TOP 1 * FROM seg.password_resets WHERE iCredId=? AND bUtilizado=0
        ORDER BY dtFechaCreacion DESC", [$iCredId]);
    }

    public static function updIncrementarIntentos($iPasswordResetId)
    {
        DB::statement("UPDATE [seg].[password_resets] SET iIntentos=iIntentos+1 WHERE iPasswordResetId=?", [$iPasswordResetId]);
    }

    public static function generarResetToken($ultimoToken)
    {
        $resetTokenPlain = Str::random(60);
        $resetTokenHash = Hash::make($resetTokenPlain);
        DB::statement("UPDATE [seg].[password_resets] SET cResetTokenHash=? WHERE iPasswordResetId=?", [$resetTokenHash, $ultimoToken->iPasswordResetId]);
        return $resetTokenPlain;
    }

    public static function updCredPasswordSinPasswordActual($iCredId, $contraseniaNueva)
    {
        DB::statement("EXEC [seg].[Sp_UPD_credPasswordSinPasswordActual] @iCredId=?, @contraseniaNueva=?", [$iCredId, $contraseniaNueva]);
    }
}
