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
            $originalContent = $response->getData(true); 

            if (isset($originalContent['user']['iCredId'])) {
                DB::select(
                    "EXEC grl.SP_INS_EnTablaDesdeJSON ?,?,?",
                    [
                        'seg',
                        'auditoria_accesos',
                        json_encode([
                            'iCredId' => $originalContent['user']['iCredId'],
                            'cIpCliente' => $this->getIPCliente(),
                            'cNavegador' => $agent->browser(),
                            'cDispositivo' => $agent->deviceType(),
                            'cSistmaOperativo' => $this->getSOCliente(),
                        ])
                    ]
                );
            }

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
