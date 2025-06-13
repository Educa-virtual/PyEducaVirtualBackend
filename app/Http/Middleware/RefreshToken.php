<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class RefreshToken
{

    /**
     * Middleware para refrescar el token JWT en cada solicitud autenticada.
     *
     * Este middleware verifica si el usuario está autenticado utilizando el guard "api".
     * Si el usuario está autenticado, genera un nuevo token JWT con un tiempo de vida renovado
     * y lo incluye en los encabezados de la respuesta.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP entrante.
     * @param \Closure $next La siguiente acción en la cadena de middleware.
     * @return \Symfony\Component\HttpFoundation\Response La respuesta HTTP con el token actualizado, si aplica.
     *
     * @throws \Exception Si ocurre un error al intentar refrescar el token.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            if (Auth::guard('api')->check()) {
                $newToken = JWTAuth::refresh(JWTAuth::getToken(), false);
                $response->headers->set('Access-Control-Expose-Headers', 'Authorization');
                $response->headers->set('Authorization', $newToken);
            }
        } catch (\Exception $e) {
            // Si el token ya no es válido, se ignora y se deja expirar normalmente
        }
        return $response;
    }
}
