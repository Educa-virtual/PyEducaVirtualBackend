<?php

namespace App\Services;

use App\Services\grl\PersonasService;
use DateTimeImmutable;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ConsultarDocumentoIdentidadService
{

    //private $token;
    private $divirApellidoNombresService;


    public function __construct()
    {
        //$this->token = env('FACTILIZA_TOKEN');
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
                    'status' => Response::HTTP_NOT_FOUND
                ];
        }
    }

    /**
     * Buscar datos en servicio web según DNI
     * @param mixed $documento Número de DNI
     * @return array Contiene mensaje, codigo status y datos de la persona
     */
    public function buscarDni($documento)
    {
        try {
            if (strlen($documento) != 8) {
                throw new Exception("El DNI debe tener 8 digitos");
            }
            $response = FactilizaService::consultarDocumento('dni', $documento);
            $respuesta = json_decode($response);
            if ($respuesta->data === null) {
                return [
                    'message' => 'No se obtuvo datos: ' . $respuesta->message,
                    'data' => [],
                    'status' => Response::HTTP_NOT_FOUND,
                ];
            }
            $respuestaFormateada = $this->formatearRespuestaDni($respuesta->data);
            $iPersId = PersonasService::actualizarPersonaConDataApi($respuestaFormateada);
            return [
                'message' => 'Se obtuvo la información del servicio ',
                'data' => $respuestaFormateada,
                'status' => $respuesta->status,
                'iPersId' => $iPersId //$iPersId  se agrego
            ];
        } catch (Exception $ex) {
            return [
                'message' => 'Error consultando servicio: ' . $ex->getMessage(),
                'data' => [],
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
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
        try {
            if (strlen($documento) != 12) {
                throw new Exception("El Carnet debe tener 12 digitos");
            }
            $response = FactilizaService::consultarDocumento('carnet', $documento);
            $respuesta = json_decode($response);
            if ($respuesta->data === null) {
                return [
                    'message' => 'No se obtuvo datos: ' . $respuesta->message,
                    'data' => [],
                    'status' => 404,
                ];
            }
            $respuestaFormateada = $this->formatearRespuestaCarnet($respuesta->data);
            return [
                'message' => 'Se obtuvo la información del servicio ',
                'data' => $respuestaFormateada,
                'status' => $respuesta->status
            ];
        } catch (Exception $ex) {
            return [
                'message' => 'Error consultando servicio: ' . $ex->getMessage(),
                'data' => [],
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
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
        try {
            if (strlen($documento) != 11) {
                throw new Exception("El RUC debe tener 11 digitos");
            }
            $response = FactilizaService::consultarDocumento('ruc', $documento);
            $respuesta = json_decode($response);
            if ($respuesta->data === null) {
                return [
                    'message' => 'No se obtuvo datos: ' . $respuesta->message,
                    'data' => [],
                    'status' => 404,
                ];
            }
            $respuestaFormateada = $this->formatearRespuestaRuc($respuesta->data);
            return [
                'message' => 'Se obtuvo la información del servicio ',
                'data' => $respuestaFormateada,
                'status' => $respuesta->status
            ];
        } catch (Exception $ex) {
            return [
                'message' => 'Error consultando servicio: ' . $ex->getMessage(),
                'data' => [],
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
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

            $estado_civil = DB::select('SELECT tec.iTipoEstCivId
                FROM grl.tipos_estados_civiles tec
                WHERE UPPER(cTipoEstCivilReniec) LIKE UPPER(?)', [trim($respuesta->estado_civil)]);
        } catch (\Exception $e) {
            // No devolver error
        }

        // Convertir multiples formatos de fecha a Y-m-d
        if (strpos($respuesta->fecha_nacimiento, '/') !== false) {
            $fecha_nacimiento = DateTimeImmutable::createFromFormat('d/m/Y', trim($respuesta->fecha_nacimiento));
            $fecha_nacimiento_formateada = date_format($fecha_nacimiento, 'Y-m-d');
        } elseif (strpos($respuesta->fecha_nacimiento, '-') == 4) {
            $fecha_nacimiento = DateTimeImmutable::createFromFormat('Y', trim($respuesta->fecha_nacimiento));
            $fecha_nacimiento_formateada = $respuesta->fecha_nacimiento;
        } elseif (strpos($respuesta->fecha_nacimiento, '-') == 2) {
            $fecha_nacimiento = DateTimeImmutable::createFromFormat('d-m-Y', trim($respuesta->fecha_nacimiento));
            $fecha_nacimiento_formateada = date_format($fecha_nacimiento, 'Y-m-d');
        } else {
            $fecha_nacimiento_formateada = NULL;
        }

        return [
            'iTipoIdentId' => "1",
            'cPersDocumento' => trim($respuesta->numero) ?: NULL,
            'cPersPaterno' => trim($respuesta->apellido_paterno),
            'cPersMaterno' => trim($respuesta->apellido_materno),
            'cPersNombre' => trim($respuesta->nombres),
            'cPersSexo' => trim($respuesta->sexo) ?: NULL,
            'dPersNacimiento' => $fecha_nacimiento_formateada,
            'iTipoEstCivId' => count($estado_civil) > 0 ? $estado_civil[0]->iTipoEstCivId : NULL,
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
