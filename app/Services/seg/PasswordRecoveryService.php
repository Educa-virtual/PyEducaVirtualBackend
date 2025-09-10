<?php

namespace App\Services\seg;

use App\Http\Requests\seg\CambiarContrasenaRequest;
use App\Mail\seg\PasswordCambiadoMail;
use App\Mail\seg\RecuperarPasswordMail;
use App\Models\seg\PasswordReset;
use App\Models\seg\Usuario;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class PasswordRecoveryService
{
    public static function enviarCodigoRecuperacion(Request $request)
    {
        self::restringirEnvios($request);
        $usuario = Usuario::selUsuarioPorCredencial($request->cCredUsuario);
        if ($usuario && !empty($usuario->cPersCorreo)) {
            PasswordReset::updAnularTokensUsuario($usuario->iCredId);
            $token = mt_rand(100000, 999999);
            PasswordReset::insToken($usuario->iCredId, Hash::make($token));
            Mail::mailer('mailer_noreply')->to($usuario->cPersCorreo)->send(new RecuperarPasswordMail($usuario, $token));
            return self::enmascararCorreo($usuario->cPersCorreo);
        } else {
            throw new Exception('El usuario no existe o no tiene un correo asociado');
        }
    }

    private static function restringirEnvios(Request $request) {
        $ipKey = 'pr_requests:' . $request->ip();
        if (RateLimiter::tooManyAttempts($ipKey, 10)) {
            throw new Exception('Demasiadas solicitudes desde esta IP. Intenta más tarde.');
        }
        RateLimiter::hit($ipKey, 180); // ventana 180s
    }

    private static function enmascararCorreo($correo)
    {
        $partes = explode("@", $correo);
        if (count($partes) != 2) {
            return $correo; // Formato inválido, retornar tal cual
        }
        $nombre = $partes[0];
        $dominio = $partes[1];

        if (strlen($nombre) <= 2) {
            $nombreEnmascarado = str_repeat("*", strlen($nombre));
        } else {
            $nombreEnmascarado = substr($nombre, 0, 1) . str_repeat("*", strlen($nombre) - 2) . substr($nombre, -1);
        }
        return $nombreEnmascarado . "@" . $dominio;
    }

    public static function validarCodigoRecuperacion(Request $request)
    {
        self::restringirEnvios($request);
        $request->validate([
            'cCredUsuario' => 'required',
            'token' => 'required|digits:6'
        ]);

        $usuario = Usuario::selUsuarioPorCredencial($request->cCredUsuario);
        if (!$usuario) {
            throw new Exception('El usuario no existe o el código no es valido');
        }
        $ultimoToken = PasswordReset::selUltimoTokenValidacion($usuario->iCredId);
        if (!$ultimoToken || now()->greaterThan($ultimoToken->dtFechaExpiracion)) {
            throw new Exception('Código expirado. Solicite un nuevo código');
        }
        if ($ultimoToken->iIntentos >= 5) {
            throw new Exception('Demasiados intentos. Solicite un nuevo código');
        }
        if ($ultimoToken->bUtilizado) {
            throw new Exception('El código ya fue utilizado. Solicite un nuevo código');
        }
        if (!Hash::check($request->token, $ultimoToken->cCodigoHash)) {
            PasswordReset::updIncrementarIntentos($ultimoToken->iPasswordResetId);
            throw new Exception('Código inválido. Vuelva a intentarlo');
        }
        return PasswordReset::generarResetToken($ultimoToken);
    }

    public static function resetPassword(CambiarContrasenaRequest $request)
    {
        self::restringirEnvios($request);
        $usuario = Usuario::selUsuarioPorCredencial($request->cCredUsuario);
        if (!$usuario) {
            throw new Exception('El usuario no existe o el código no es valido');
        }
        $ultimoToken = PasswordReset::selUltimoTokenValidacion($usuario->iCredId);
        if (!$ultimoToken || now()->greaterThan($ultimoToken->dtFechaExpiracion)) {
            throw new Exception('Código expirado. Solicite un nuevo código');
        }
        if (!Hash::check($request->cResetToken, $ultimoToken->cResetTokenHash)) {
            throw new Exception('Código inválido. Vuelva a intentarlo');
        }
        if ($ultimoToken->bUtilizado) {
            throw new Exception('El código ya fue utilizado. Solicite un nuevo código');
        }
        if ($request->contrasenaNueva!=$request->confirmarContrasena) {
            throw new Exception('Las contraseñas no coinciden');
        }
        PasswordReset::updCredPasswordSinPasswordActual($usuario->iCredId, $request->contrasenaNueva);
        PasswordReset::updAnularTokensUsuario($usuario->iCredId);
        Mail::mailer('mailer_noreply')->to($usuario->cPersCorreo)->send(new PasswordCambiadoMail($usuario));
    }
}
