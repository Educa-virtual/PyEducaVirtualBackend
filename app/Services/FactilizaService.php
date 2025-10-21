<?php

namespace App\Services;

use Exception;

class FactilizaService
{
    private static function getTokenConsulta()
    {
        return env('FACTILIZA_TOKEN_CONSULTA');
    }

    private static function getTokenWhatsApp()
    {
        return env('FACTILIZA_TOKEN_WHATSAPP');
    }

    private static function getInstanciaWhatsApp()
    {
        return env('FACTILIZA_INSTANCIA_WHATSAPP');
    }

    /**
     * Envía un mensaje de WhatsApp al número especificado.
     *
     * @param string $numero Número de teléfono del destinatario. Debe anteponer el código del país sin el signo '+'
     * @param string $mensaje Contenido del mensaje a enviar
     * @return string Respuesta: {status: 200, success: true, message: "Mensaje Enviado"}
     * @throws Exception Si el número tiene menos de 6 caracteres o si ocurre un error en el envío (cURL)
     */
    public static function enviarMensajeWhatsApp($numero, $mensaje)
    {
        // Eliminar el carácter '+' si existe
        $numero = str_replace('+', '', $numero);

        // Validar que el número tenga al menos 6 caracteres
        if (strlen($numero) < 6) {
            throw new Exception("El número de teléfono debe tener al menos 6 caracteres");
        }

        // Si el número tiene 9 caracteres y empieza con 9, agregar '51' adelante, se asume que es de Perú
        if (strlen($numero) === 9 && substr($numero, 0, 1) === '9') {
            $numero = '51' . $numero;
        }

        $token = self::getTokenWhatsApp();
        $instancia = self::getInstanciaWhatsApp();
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://apiwsp.factiliza.com/v1/message/sendtext/$instancia",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'number' => $numero,
                'text' => $mensaje
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new Exception("Error al enviar mensaje: " . $err);
        }
        return $response;
    }

    /**
     * Consulta información de un documento en la API de Factiliza.
     *
     * @param string $tipo Tipo de documento a consultar: 'dni', 'ruc' o 'carnet'
     * @param string $documento Número del documento a consultar
     * @return string Respuesta JSON con la información del documento
     * @throws Exception Si el tipo de documento no es soportado o si ocurre un error en la consulta (cURL)
     */
    public static function consultarDocumento($tipo, $documento)
    {
        switch ($tipo) {
            case 'dni':
                $url = "https://api.factiliza.com/v1/dni/info/$documento";
                break;
            case 'ruc':
                $url = "https://api.factiliza.com/v1/ruc/info/$documento";
                break;
            case 'carnet':
                $url = "https://api.factiliza.com/v1/cee/info/$documento";
                break;
            default:
                throw new Exception("Tipo de documento no soportado: $tipo");
        }

        $token = self::getTokenConsulta();
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $token
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            throw new Exception("Error al consultar documento:" . $err);
        }
        return $response;
    }
}
