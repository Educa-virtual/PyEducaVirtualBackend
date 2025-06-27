<?php

namespace App\Http\Middleware;

use Closure;
use hisorange\BrowserDetect\Parser as Browser;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuditoriaAccesos
{
    /**
     * Middleware para registrar auditorías de accesos exitosos y fallidos
     *
     * Este middleware realiza las siguientes acciones:
     * - Si la respuesta contiene información de usuario (`iCredId`), registra
     *   los detalles del acceso exitoso.
     * - Si la respuesta contiene un error, registra un intento fallido de
     *   acceso con la información correspondiente.
     *
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * @var \Illuminate\Http\JsonResponse $response
         */
        $response = $next($request);

        // Herramienta para identificar el dispositivo del cliente.
        $agent = new Agent();

        // Registro de accesos exitosos
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            $originalContent = $response->getData(true);
            //
            if (isset($originalContent['data']['user']['iCredId'])) {
                DB::select(
                    "EXEC grl.SP_INS_EnTablaDesdeJSON ?,?,?",
                    [
                        'seg',
                        'auditoria_accesos',
                        json_encode([
                            'iCredId' => $originalContent['data']['user']['iCredId'],
                            'cIpCliente' => $this->getIPCliente(),
                            'cNavegador' => Browser::browserFamily(), //$agent->browser(),
                            'cDispositivo' => Browser::deviceType(),
                            'cSistmaOperativo' => Browser::platformName(),
                        ])
                    ]
                );
            }

            // Registro de accesos fallidos
            if (isset($originalContent['message']) and isset($originalContent['status']) and $originalContent['status'] == 'Error') {
                $pass = isset($request->pass) ? (strlen($request->pass) >= 3 ? '***' . substr($request->pass, 3) : str_repeat('*', strlen($request->pass))) : null;
                if (strlen($pass) > 30) {
                    $pass = substr($pass, 0, 30);
                }
                DB::select(
                    "EXEC grl.SP_INS_EnTablaDesdeJSON ?,?,?",
                    [
                        'seg',
                        'auditoria_accesos_fallidos',
                        json_encode([
                            'cLogin' => $request->user,
                            'cPassword' => $pass,
                            'cMotivo' => json_encode($originalContent['message']),
                            'cIpCliente' => $this->getIPCliente(),
                            'cNavegador' => Browser::browserFamily(),
                            'cDispositivo' => Browser::deviceType(),
                            'cSistmaOperativo' => Browser::platformName(),
                        ])
                    ]
                );
            }
            $response->setData($originalContent);
        }
        return $response;
    }

    /**
     * Obtiene la dirección IP del cliente que realiza la solicitud.
     *
     * Este método maneja diferentes escenarios como:
     * - IP desde una conexión directa.
     * - IP desde un proxy o red compartida.
     *
     * @return string
     *   La dirección IP del cliente.
     */
    function getIPCliente(): String
    {
        $ipAddress = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // IP de internet compartido
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // IP desde un proxy
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            // IP directa
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }

        // Manejo de múltiples IPs (cuando se usa un proxy)
        if (strpos($ipAddress, ',') !== false) {
            $ipList = explode(',', $ipAddress);
            $ipAddress = trim($ipList[0]);
        }

        return $ipAddress;
    }
}
