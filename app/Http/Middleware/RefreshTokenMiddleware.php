<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class RefreshTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            if (Auth::guard('api')->check()) {
                $newToken = JWTAuth::refresh(JWTAuth::getToken(), false);//Auth::guard('api')->refresh();
                $response->headers->set('Access-Control-Expose-Headers', 'Authorization');
                $response->headers->set('Authorization', $newToken);
            }
        } catch (\Exception $e) {
            // Si el token ya no es válido, se ignora y se deja expirar normalmente
        }

        return $response;
    }
}
