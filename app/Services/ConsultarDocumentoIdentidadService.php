<?php

namespace App\Services;

use DateTimeImmutable;
use Illuminate\Support\Facades\DB;

class ConsultarDocumentoIdentidadService
{

    private $token;
    private $divirApellidoNombresService;
        
    
    public function __construct()
    {
        $this->token = env('FACTILIZA_TOKEN');
        $this->divirApellidoNombresService = new DividirApellidoNombresService();
    }

    /**
     * Consultar datos segun tipo de documento de identidad
     * @param mixed $tipo_documento Tipo de documento de identidad
     * @param mixed $documento Número de documento de identidad
     * @return array Contiene mensaje, codigo status y datos de la persona
     */
    public function buscar($tipo_documento, $documento)
    {
        switch ($tipo_documento) {
            case 1:
                return $this->buscarDni($documento);
                break;
            case 2:
                return $this->buscarRuc($documento);
                break;
            case 3:
                return $this->buscarCarnet($documento);
                break;
            default:
                return [
                    'message' => 'Tipo de identificación no existe',
                    'data' => [],
                    'status' => 404];
        }
    }

    /**
     * Buscar datos en servicio web según DNI
     * @param mixed $documento Número de DNI
     * @return array Contiene mensaje, codigo status y datos de la persona
     */
    public function buscarDni($documento)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.factiliza.com/v1/dni/info/" . $documento,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $this->token
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return [
                'message' => 'Error consultando servicio: ' . $err,
                'data' => [],
                'status' => 500
            ];
        } else {
            $respuesta = json_decode($response);
            if( $respuesta->data === null ) {
                return [
                    'message' => 'No se obtuvo datos: ' . $respuesta->message,
                    'data' => [],
                    'status' => 404,
                ];
            }
            return [
                'message' => 'Se obtuvo la información',
                'data' => $this->formatearRespuestaDni($respuesta->data),
                'status' => $respuesta->status,
            ];
        }
    }

    /**
     * Buscar datos en servicio web según Carnet de extranjería
     * @param mixed $documento Carnet de extranjería
     * @return array Contiene mensaje, codigo status y datos de la persona
     */
    public function buscarCarnet($documento)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.factiliza.com/v1/cee/info/" . $documento,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $this->token
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return [
                'message' => 'Error consultando servicio: ' . $err,
                'data' => [],
                'status' => 500
            ];
        } else {
            $respuesta = json_decode($response);
            if( $respuesta->data === null ) {
                return [
                    'message' => 'No se obtuvo datos: ' . $respuesta->message,
                    'data' => [],
                    'status' => 404,
                ];
            }
            return [
                'message' => 'Se obtuvo la información',
                'data' => $this->formatearRespuestaCarnet($respuesta->data),
                'status' => $respuesta->status,
            ];
        }
    }

    /**
     * Buscar datos en servicio web según RUC
     * @param mixed $documento Registro Único de Contribuyente
     * @return array Contiene mensaje, codigo status y datos de la persona
     */

    private function buscarRuc($documento)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.factiliza.com/v1/ruc/info/" . $documento,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $this->token
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return [
                'message' => 'Error consultando servicio: ' . $err,
                'data' => [],
                'status' => 500
            ];
        } else {
            $respuesta = json_decode($response);
            if( $respuesta->data === null ) {
                return [
                    'message' => 'No se obtuvo datos: ' . $respuesta->message,
                    'data' => [],
                    'status' => 404,
                ];
            }
            return [
                'message' => 'Se obtuvo la información',
                'data' => $this->formatearRespuestaRuc($respuesta->data),
                'status' => $respuesta->status,
            ];
        }
    }

    /**
     * Formatear respuesta de servicio web para DNI
     * @param object $respuesta Respuesta del servicio web
     * @return array Datos de la persona segun tabla grl.personas
     */
    private function formatearRespuestaDni($respuesta)
    {
        try {
            $ubigeo = DB::select('SELECT p.iDptoId, p.iPrvnId, d.iDsttId 
                FROM grl.distritos d 
                    INNER JOIN grl.provincias p ON p.iPrvnId = d.iPrvnId
                WHERE cDsttCodigoINEI = ?', [trim($respuesta->ubigeo_sunat)]);
        } catch (\Exception $e) {
            // No devolver error
        }

        $fecha_nacimiento = DateTimeImmutable::createFromFormat('d/m/Y', trim($respuesta->fecha_nacimiento));
        $fecha_nacimiento_formateada = date_format($fecha_nacimiento, 'Y-m-d');;

        return [
            'iTipoIdentId' => "1",
            'cPersDocumento' => trim($respuesta->numero) ?: NULL,
            'cPersPaterno' => trim($respuesta->apellido_paterno),
            'cPersMaterno' => trim($respuesta->apellido_materno),
            'cPersNombre' => trim($respuesta->nombres),
            'cPersSexo' => trim($respuesta->sexo) ?: NULL,
            'dPersNacimiento' => $fecha_nacimiento_formateada,
            'iTipoEstCivId' => trim($respuesta->estado_civil) ?: NULL,
            'iNacionId' => "193",
            'cPersFotografia' => trim($respuesta->foto),
            'cPersDomicilio' => trim($respuesta->direccion),
            'iPaisId' => "589",
            'iDptoId' => count($ubigeo) > 0 ? $ubigeo[0]->iDptoId : NULL,
            'iPrvnId' => count($ubigeo) > 0 ? $ubigeo[0]->iPrvnId : NULL,
            'iDsttId' => count($ubigeo) > 0 ? $ubigeo[0]->iDsttId : NULL,
            'cEstUbigeo' => trim($respuesta->ubigeo_sunat),
        ];
    }

    /** Formatear respuesta de servicio web para Carnet de Extranjería
     * @param object $respuesta Respuesta del servicio web
     * @return array Datos de la persona segun tabla grl.personas
     */
    private function formatearRespuestaCarnet($respuesta)
    {
        return [
            'iTipoIdentId' => "3",
            'cPersDocumento' => trim($respuesta->numero) ?: NULL,
            'cPersPaterno' => trim($respuesta->apellido_paterno),
            'cPersMaterno' => trim($respuesta->apellido_materno),
            'cPersNombre' => trim($respuesta->nombres),
        ];
    }

    /** Formatear respuesta de servicio web para Carnet de Extranjería
     * @param object $respuesta Respuesta del servicio web
     * @return array Datos de la persona segun tabla grl.personas
     */
    private function formatearRespuestaRuc($respuesta)
    {
        try {
            $ubigeo = DB::select('SELECT p.iDptoId, p.iPrvnId, d.iDsttId 
                FROM grl.distritos d 
                    INNER JOIN grl.provincias p ON p.iPrvnId = d.iPrvnId
                WHERE cDsttCodigoINEI = ?', [trim($respuesta->ubigeo_sunat)]);
        } catch (\Exception $e) {
            // No devolver error
        }

        $apellidos_nombres = $this->divirApellidoNombresService->dividir($respuesta->nombre_o_razon_social);

        return [
            'iTipoIdentId' => "3",
            'cPersDocumento' => trim($respuesta->numero) ?: NULL,
            'cPersPaterno' => $apellidos_nombres['paterno'],
            'cPersMaterno' => $apellidos_nombres['materno'],
            'cPersNombre' => $apellidos_nombres['nombres'],
            'cPersRazonSocialNombre' => $respuesta->nombre_o_razon_social,
            'cPersDomicilio' => trim($respuesta->direccion),
            'iPaisId' => "589",
            'iDptoId' => count($ubigeo) > 0 ? $ubigeo[0]->iDptoId : NULL,
            'iPrvnId' => count($ubigeo) > 0 ? $ubigeo[0]->iPrvnId : NULL,
            'iDsttId' => count($ubigeo) > 0 ? $ubigeo[0]->iDsttId : NULL,
            'cEstUbigeo' => trim($respuesta->ubigeo_sunat),
        ];
    }
}