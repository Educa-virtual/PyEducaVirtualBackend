<?php

namespace App\Http\Controllers\seg;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\seg\CambiarContrasenaRequest;
use Illuminate\Http\Request;
use App\Services\seg\PasswordRecoveryService;
use Exception;


class PasswordRecoveryController extends Controller
{
    public function enviarCodigoRecuperacion(Request $request)
    {
        try {
            $correo = PasswordRecoveryService::enviarCodigoRecuperacion($request);
            return FormatearMensajeHelper::ok('Se ha enviado el correo con el código de recuperación de contraseña', ['correo' => $correo]);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function validarCodigoRecuperacion(Request $request)
    {
        try {
            $resetToken = PasswordRecoveryService::validarCodigoRecuperacion($request);
            return FormatearMensajeHelper::ok('El código es válido', ['token' => $resetToken]);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    // 3) Reset de contraseña
    public function resetPassword(CambiarContrasenaRequest $request)
    {
        try {
            PasswordRecoveryService::resetPassword($request);
            return FormatearMensajeHelper::ok('Se ha cambiado su contraseña. Puede iniciar sesión con su nueva contraseña.');
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
