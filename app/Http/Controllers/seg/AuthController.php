<?php

namespace App\Http\Controllers\seg;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Requests\seg\CambiarClaveRequest;
use App\Http\Requests\seg\CambiarContrasenaRequest;
use App\Http\Requests\seg\LoginUsuarioRequest;
use App\Models\User;
use App\Services\seg\UsuariosService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController
{

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login de usuario",
     *     description="Autentica un usuario y retorna un token JWT junto con la data del usuario.",
     *     tags={"Autenticación"},
     *     @OA\Parameter(
     *         name="user",
     *         in="query",
     *         required=true,
     *         description="Usuario",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="pass",
     *         in="query",
     *         required=true,
     *         description="Contraseña del usuario",
     *         @OA\Schema(type="string", minLength=6)
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Errores de autenticación posibles:
     *                      - Usuario o contraseña incorrectos
     *                      - Su usuario está desactivado, contacte con el área de soporte
     *                      - Su cuenta ha expirado, contacte con el área de soporte)",
     *         @OA\JsonContent(
     *             @OA\Property(property="validated", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Usuario o contraseña incorrectos")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inicio de sesión correcto",
     *         @OA\JsonContent(
     *             @OA\Property(property="accessToken", type="string", description="JWT token"),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer", description="Tiempo de duración del token"),
     *             @OA\Property(property="user", type="object", description="Data del usuario")
     *         )
     *     )
     * )
     */
    public function login(LoginUsuarioRequest $request)
    {
        try {
            $usuario = User::login($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $usuario);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function actualizarContrasenaUsuario(CambiarContrasenaRequest $request)
    {
        try {
            $usuario = Auth::user();
            UsuariosService::actualizarContrasenaUsuario($usuario, $request);
            return FormatearMensajeHelper::ok('Se ha actualizado la contraseña exitosamente');
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }

        /*try {
            $data =

            if ($data[0]->iPersId > 0) {
                return new JsonResponse(
                    ['validated' => true, 'message' => 'Se actualizó la contraseña exitosamente '],
                    Response::HTTP_OK
                );
            } else {
                return new JsonResponse(
                    ['validated' => false, 'message' => 'No se pudo actualizar la contraseña'],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54)],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new JsonResponse($response, $codeResponse);*/
    }
}
