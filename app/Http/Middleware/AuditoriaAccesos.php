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
            // if (isset($originalContent['user'])) {
            //     $userExist = DB::select(
            //         "EXEC grl.SP_SEL_DesdeTablaOVista ?,?,?,?",
            //         [
            //             'seg',
            //             'credenciales',
            //             'cCredUsuario',
            //             'cCredUsuario=' . $request->user,
            //         ]
            //     );

            //     DB::select(
            //         "EXEC grl.SP_INS_EnTablaDesdeJSON ?,?,?",
            //         [
            //             'seg',
            //             'auditoria_accesos',
            //             json_encode([
            //                 'iCredId' => $originalContent['user']['iCredId'],
            //                 'cIpCliente' => $this->getIPCliente(),
            //                 'cNavegador' => $agent->browser(),
            //                 'cDispositivo' => $agent->deviceType(),
            //                 'cSistmaOperativo' => $agent->platform(),
            //             ])
            //         ]
            //     );
            //     $originalContent['extra_info'] = 'Inicio de sesión exitoso';
            //     $originalContent['userExist'] = $userExist;
            // }

            // // Verifica la presencia de 'error' y modifica la respuesta
            // if (isset($originalContent['error'])) {
            //     DB::select(
            //         "EXEC grl.SP_INS_EnTablaDesdeJSON ?,?,?",
            //         [
            //             'seg',
            //             'auditoria_accesos_fallidos',
            //             json_encode([
            //                 'cLogin' => $request->user,
            //                 'cPassword' => $request->pass,
            //                 'cMotivo' => $originalContent['error'],
            //                 'cIpCliente' => $this->getIPCliente(),
            //                 'cNavegador' => $agent->browser(),
            //                 'cDispositivo' => $agent->deviceType(),
            //                 'cSistmaOperativo' => $agent->platform(),
            //             ])
            //         ]
            //     );
            //     $originalContent['extra_info'] = 'Error de inicio de sesión';
            // }

            // Sobrescribir el contenido de la respuesta
            $response->setData($originalContent);
        }

        return $response;
    }

    function getIPCliente(): String
    {
        $ipAddress = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // Check if IP is from shared internet
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Check if IP is passed from proxy
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            // Check if IP is from remote address
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }

        // Handle multiple IPs (when using proxies)
        if (strpos($ipAddress, ',') !== false) {
            $ipList = explode(',', $ipAddress);
            $ipAddress = trim($ipList[0]);
        }

        return $ipAddress;
    }
}
