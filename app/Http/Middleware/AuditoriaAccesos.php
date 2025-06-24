<?php

namespace App\Http\Middleware;

use Closure;
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
        //file_put_contents("D:\\audit.txt","Hola");
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
                file_put_contents("D:\\audit.txt","Si ingresa");
                DB::select(
                    "EXEC grl.SP_INS_EnTablaDesdeJSON ?,?,?",
                    [
                        'seg',
                        'auditoria_accesos',
                        json_encode([
                            'iCredId' => $originalContent['data']['user']['iCredId'],
                            'cIpCliente' => $this->getIPCliente(),
                            'cNavegador' => $agent->browser(),
                            'cDispositivo' => $agent->deviceType(),
                            'cSistmaOperativo' => $this->getSOCliente(),
                        ])
                    ]
                );
            }

            // Registro de accesos fallidos
            if (isset($originalContent['message']) AND isset($originalContent['status']) AND $originalContent['status'] == 'Error') {
                DB::select(
                    "EXEC grl.SP_INS_EnTablaDesdeJSON ?,?,?",
                    [
                        'seg',
                        'auditoria_accesos_fallidos',
                        json_encode([
                            'cLogin' => $request->user,
                            'cPassword' => $request->pass,
                            'cMotivo' => json_encode($originalContent['message']),
                            'cIpCliente' => $this->getIPCliente(),
                            'cNavegador' => $agent->browser(),
                            'cDispositivo' => $agent->deviceType(),
                            'cSistmaOperativo' => $this->getSOCliente(),
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

    /**
     * Obtiene el sistema operativo del cliente basado en el agente de usuario
     * (User-Agent).
     *
     * Este método analiza la cadena `HTTP_USER_AGENT` para identificar el
     * sistema operativo
     * desde una lista predefinida.
     *
     * @return string
     * El nombre del sistema operativo del cliente o 'SO Desconocido' si no
     * se identifica.
     */
    function getSOCliente() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        $osArray = [
            'Windows 10' => '/Windows NT 10.0/i',
            'Windows 8.1' => '/Windows NT 6.3/i',
            'Windows 8' => '/Windows NT 6.2/i',
            'Windows 7' => '/Windows NT 6.1/i',
            'Windows Vista' => '/Windows NT 6.0/i',
            'Windows Server 2003/XP x64' => '/Windows NT 5.2/i',
            'Windows XP' => '/Windows NT 5.1/i',
            'Windows 2000' => '/Windows NT 5.0/i',
            'Mac OS X' => '/Mac OS X/i',
            'Mac OS' => '/Mac_PowerPC/i',
            'Linux' => '/Linux/i',
            'Ubuntu' => '/Ubuntu/i',
            'iPhone' => '/iPhone/i',
            'iPod' => '/iPod/i',
            'iPad' => '/iPad/i',
            'Android' => '/Android/i',
            'BlackBerry' => '/BlackBerry/i',
            'Mobile' => '/Mobile/i'
        ];

        foreach ($osArray as $os => $regex)
            if (preg_match($regex, $userAgent))
                return $os;

        return 'SO Desconocido';
    }
}
