<?php

namespace App\Helpers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ProteccionCorreoHelper {

    public static function validarEnvioPorIp($ip) {
        $ipKey = 'pr_requests:' . $ip;
        if (RateLimiter::tooManyAttempts($ipKey, 10)) {
            throw new Exception('Demasiadas solicitudes desde esta IP. Intenta más tarde.');
        }
        RateLimiter::hit($ipKey, 180); // ventana 180s
    }
}
