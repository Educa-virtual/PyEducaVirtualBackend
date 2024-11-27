<?php

namespace App\Http\Middleware;

use Closure;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AuditoriaAccesos
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * @var \Illuminate\Http\JsonResponse $response
         */
        $response = $next($request);

        $agent = new Agent();

        if ($response instanceof \Illuminate\Http\JsonResponse) {
            $originalContent = $response->getData(true); // Obtiene el contenido como array

            // Verifica la presencia de 'user' y modifica la respuesta
            if (isset($originalContent['user'])) {
                DB::select(
                    "EXEC grl.SP_INS_EnTablaDesdeJSON ?,?,?",
                    [
                        'seg',
                        'auditoria_accesos',
                        json_encode([
                            'iCredId' => $originalContent['user']['iCredId'],
                            'cIpCliente' => $_SERVER["REMOTE_ADDR"],
                            'cNavegador' => $agent->browser(),
                            'cDispositivo' => $agent->deviceType(),
                            'cSistmaOperativo' => $agent->platform(),
                        ])
                    ]
                );
            }

            // Verifica la presencia de 'error' y modifica la respuesta
            if (isset($originalContent['error'])) {
                DB::select(
                    "EXEC grl.SP_INS_EnTablaDesdeJSON ?,?,?",
                    [
                        'seg',
                        'auditoria_accesos_fallidos',
                        json_encode([
                            'cLogin' => $request->user,
                            'cPassword' => $request->pass,
                            'cMotivo' => $originalContent['error'],
                            'cIpCliente' => $_SERVER["REMOTE_ADDR"],
                            'cNavegador' => $agent->browser(),
                            'cDispositivo' => $agent->deviceType(),
                            'cSistmaOperativo' => $agent->platform(),
                        ])
                    ]
                );
            }

            // Sobrescribir el contenido de la respuesta
            $response->setData($originalContent);
        }

        return $response;

    }
}
