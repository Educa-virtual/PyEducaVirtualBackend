<?php

namespace App\Http\Controllers\api\acad;

use DateTime;
use Exception;
use App\Helpers\ResponseHandler;
use App\Helpers\VerifyHash;
use Dompdf\Options;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\Console\Output\ConsoleOutput;

use App\Services\ConsultarDocumentoIdentidadService;
use App\Services\ParseSqlErrorService;
use Carbon\Carbon; // Agrega esta línea para importar Carbon

class GestionInstitucionalController extends Controller
{
    private $consultarDocumentoIdentidadService;

    public function __construct()
    {
        $this->consultarDocumentoIdentidadService = new ConsultarDocumentoIdentidadService;

    }
    // no tocar
    public function listarPersonalIes(Request $request)
    {
        $solicitud = [
        $request->iSedeId,
        $request->iYAcadId
        ];

        $query = DB::select("EXEC acad.SP_SEL_listarPersonalIesXiSedeXiYAcadId ?,?", //actualizado
        $solicitud);

        try {
        // Ensure this is inside a valid function or method
        $response = [
            'validated' => true,
            'message' => 'se obtuvo la información',
            'data' => $query,
        ];

        $estado = 201;
        } catch (Exception $e) {
        $response = [
            'validated' => false,
            'message' => $e->getMessage(),
            'data' => [],
        ];
        $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }

    public function insertMaestroDetalle(Request $request)
    {
        $solicitud = [

            $request->esquema,       //-- Esquema de la tabla maestra
            $request->tablaMaestra, //NVARCHAR(128),   -- Nombre de la tabla maestra
            $request->datosJSONMaestro, // NVARCHAR(MAX), -- Datos en formato JSON para la tabla maestra
            $request->tablaDetalle, // NVARCHAR(128),   -- Nombre de la tabla detalle
            $request->datosJSONDetalles, // NVARCHAR(MAX), -- Datos en formato JSON (array) para los detalles
            $request->campoFK // NVARCHAR(128)

        ];

        $query = DB::select("EXEC grl.SP_INS_EnTablaMaestroDetalleDesdeJSON ?,?,?,?,?,?", //actualizado
        $solicitud);

        try {
        $response = [
            'validated' => true,
            'message' => 'se obtuvo la información',
            'data' => $query,
        ];

        $estado = 201;
        } catch (Exception $e) {
        $response = [
            'validated' => false,
            'message' => $e->getMessage(),
            'data' => [],
        ];
        $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }

    public function insertMaestro(Request $request)
    {
        $solicitud = [
            $request->esquema,    //NVARCHAR(128),   -- Esquema de la tabla
            $request->tabla,      //NVARCHAR(128),    -- Nombre de la tabla
            $request->datosJSON,  //NVARCHAR(MAX) -- Datos en formato JSON
        ];

        $query = DB::select("EXEC grl.SP_INS_EnTablaDesdeJSON ?,?,?", //actualizado
        $solicitud);

        try {
        $response = [
            'validated' => true,
            'message' => 'se obtuvo la información',
            'data' => $query,
        ];

        $estado = 201;
        } catch (Exception $e) {
        $response = [
            'validated' => false,
            'message' => $e->getMessage(),
            'data' => [],
        ];
        $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }



    public function updateMaestro(Request $request)
    {
        //    $json = json_encode($request->json);
        //    $opcion = $request->_opcion;
        $condiciones = json_encode(
            [
                'COLUMN_NAME' => $request->campo,
                'VALUE' => $request->condicion
            ]
        );

        $solicitud = [
            $request->esquema,     // NVARCHAR(128),          -- Esquema de la tabla
            $request->tabla,     // NVARCHAR(128),           -- Nombre de la tabla
            $request->json,  // NVARCHAR(MAX),       -- Datos en formato JSON para la actualización
            $condiciones // NVARCHAR(MAX)  -- JSON con condiciones para el WHERE (Array de condiciones AND)
        ];

        //@json = N'[{  "jmod": "acad", "jtable": "calendario_academicos"}]'
        $query = DB::select(
            "EXEC grl.SP_UPD_EnTablaConJSON ?,?,?,?",
            $solicitud
        );
        //  [$json, $opcion ]);

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 201;
        } catch (Exception $e) {
        $response = [
            'validated' => false,
            'message' => $e->getMessage(),
            'data' => [],
        ];

        $estado = 500;
        }
        return new JsonResponse($response, $estado);
    }

    public function deleteMaestro(Request $request)
    {
        //    $json = json_encode($request->json);
        //    $opcion = $request->_opcion;

        $solicitud = [
            $request->esquema, //NVARCHAR(128),       -- Nombre del esquema
            $request->tabla, // NVARCHAR(128),   -- Nombre de la tabla principal
            $request->campo, //NVARCHAR(128),       -- Nombre del campo ID de la tabla principal
            $request->valorId, // BIGINT,              -- Valor del ID a eliminar
            // $TablaHija = null //NVARCHAR(128) = NULL   -- Nombre de la tabla hija (opcional)
        ];

        //@json = N'[{  "jmod": "acad", "jtable": "calendario_academicos"}]'
        $query = DB::select(
            "EXEC grl.SP_DEL_RegistroConTransaccion ?,?,?,?",
            $solicitud
        );
        //  [$json, $opcion ]);

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 201;
        } catch (Exception $e) {
            $response = [
                'validated' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ];

            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }

    public function reporteHorasNivelGrado(Request $request)
    {

        $solicitud = [
            $request->iNivelTipoId, //INT,
            $request->iProgId, //INT,
            $request->iConfigId, //INT,
            $request->iYAcadId, //INT
        ];

        //@json = N'[{  "jmod": "acad", "jtable": "calendario_academicos"}]'
        $query = DB::select(
            "EXEC acad.SP_SEL_generarHorasGradosSeccionesCiclosXiNivelTipoId ?,?,?,?",
            $solicitud
        );
        //  [$json, $opcion ]);

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 201;
        } catch (Exception $e) {
            $response = [
                'validated' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ];

            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }
    public function reporteSeccionesNivelGrado(Request $request)
    {

        $solicitud = [
            $request->iNivelTipoId, //INT,
            $request->iConfigId, //INT,
        ];

        //@json = N'[{  "jmod": "acad", "jtable": "calendario_academicos"}]'
        $query = DB::select(
            "EXEC acad.SP_SEL_generarGradosSeccionesXiNivelTipoIdXiConfigId ?,?",
            $solicitud
        );
        //  [$json, $opcion ]);

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 201;
        } catch (Exception $e) {
            $response = [
                'validated' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ];

            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }

    public function reportePDFResumenAmbientes(Request $request)
    {
        // Decodificar JSON a un arreglo asociativo
        $secciones = $request->secciones;
        $r_horas = $request->r_horas;
        $perfil = $request->perfil;
        $configuracion = $request->configuracion;


        $cTipoSectorNombre = $perfil["cTipoSectorNombre"];
        $cPersNombreLargo = $perfil["cPersNombreLargo"];
        $cPersDocumento = $perfil["cPersDocumento"];
        $cPerfilNombre = $perfil["cPerfilNombre"];
        $cNivelTipoNombre = $perfil["cNivelTipoNombre"];
        $cNivelNombre = $perfil["cNivelNombre"];
        $cIieeNombre = $perfil["cIieeNombre"];
        $cIieeCodigoModular = $perfil["cIieeCodigoModular"];

        $cYAcadNombre = $configuracion[0]["cYAcadNombre"];
        // $cEstadoConfigNombre = $perfil["cEstadoConfigNombre"];
        // $cSedeNombre = $perfil["cSedeNombre"];
        // $cModalServId = $perfil["cModalServId"];
        // $cYAcadNombre = $perfil["cYAcadNombre"];
        // $iProgId = $perfil["iProgId"];




        switch($request->iNivelTipoId){
            case 3:
                $title = 'Primaria'; break;
            case 4:
                $title = 'Secundaria'; break;

            default :
                $title = 'Sin nivel';
                break;
        }


        $imagePath = public_path('images\logo_IE\dremo.jpg');
        $imageData = base64_encode(file_get_contents($imagePath));
        $region = 'data:image/jpeg;base64,' . $imageData;

        $imagePath = public_path('images\logo_IE\juan_XXIII.jpg');
        $imageData = base64_encode(file_get_contents($imagePath));
        $insignia = 'data:image/jpeg;base64,' . $imageData;

        $imagePath = public_path('images\logo_IE\Logo-buho.jpg');
        $imageData = base64_encode(file_get_contents($imagePath));
        $virtual = 'data:image/jpeg;base64,' . $imageData;


        $respuesta = [
            "totalHorasPendientes" => $request->totalHorasPendientes,
            "title" => $title,
            "fecha" => date("F j, Y, g:i a"),
            "total_aulas" => $request->total_aulas,
            "r_horas" =>  $r_horas,
            "secciones" =>  $secciones,
            "dre" => "DRE MOQUEGUA UGEL",
            "totalHoras" => $request->totalHoras,
            "bConfigEsBilingue" => $request->bConfigEsBilingue,
            "contador" => 1,
            "imageLogo" => $region,// Ruta absoluta
            "logoVirtual" => $virtual,// Ruta absoluta
            "logoInsignia" => $insignia,// Ruta absoluta
            "cIieeCodigoModular" => $cIieeCodigoModular,
            "cIieeNombre" =>$cIieeNombre,
            "cNivelNombre" =>$cNivelNombre,
            "cYAcadNombre" => $cYAcadNombre,
            "cPersNombreLargo" =>$cPersNombreLargo,
            "cNivelTipoNombre" =>$cNivelTipoNombre,
            // "cPerfilNombre" =>$cPerfilNombre,
            // "cPersDocumento" =>$cPersDocumento,
            // "cPersNombreLargo" =>$cPersNombreLargo,
            // "cTipoSectorNombre" =>$cTipoSectorNombre,

        ];
//portrait landscape
        $pdf = Pdf::loadView('resumen_reporte_ambientes_primaria', $respuesta)
            ->setPaper('a4', 'landscape')
            ->stream('reporte.pdf');
        return $pdf;
    }

    public function buscarDni(Request $request){

        $servicio = $this->consultarDocumentoIdentidadService->buscar($request->iTipoIdentId, $request->cPersDocumento);
        $response = ['validated' => true, 'message' => $servicio['message'], 'data' => $servicio['data']];
        $codeResponse = $servicio['status'];
        return new JsonResponse($response, $codeResponse);
    }
    // consulta para traslados
    public function obtenerInformacionEstudianteDNI(Request $request)
    {
        $solicitud = [
            $request->dni]; //INT,

        //41789603
        $query = DB::select("EXEC acad.SP_SEL_ObtenerInformacionEsdudianteXdni ?", $solicitud);

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 201;
        } catch (Exception $e) {
            $response = [
                'validated' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ];
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }

     // consulta para traslados
     public function obtenerCredencialesSede(Request $request)
     {
         $solicitud = [
             $request->iSedeId, //INT,
             $request->option]; //INT,

         //41789603
         $query = DB::select("EXEC seg.SP_SEL_ObtenerCredencialesXiSedeId ?,?", $solicitud);

         try {
             $response = [
                 'validated' => true,
                 'message' => 'se obtuvo la información',
                 'data' => $query,
             ];

             $estado = 201;
         } catch (Exception $e) {
             $response = [
                 'validated' => false,
                 'message' => $e->getMessage(),
                 'data' => [],
             ];
             $estado = 500;
         }

         return new JsonResponse($response, $estado);
     }

    public function importarDocente_IE(Request $request)
    {
        $json   = $request->data;
        $iSedeId = $request->iSedeId;
        $iYAcadId = $request->iYAcadId;

        // Variables para almacenar resultados
        $procesados = [];
        $observados = [];

        foreach ($json as $item) {
            // Extraer valores del JSON en cada iteración
            // $cTipoIdentId    = $item["cTipoIdentId"];
            // $cPersDocumento  = $item["cPersDocumento"]; // convertir texto
            // $cPersPaterno    = $item["cPersPaterno"];
            // $cPersMaterno    = $item["cPersMaterno"];
            // $cPersNombre     = $item["cPersNombre"];
            // $cPersSexo       = $item["cPersSexo"];
            // $dPersNacimiento = $item["dPersNacimiento"]; // convertir en date
            // $iHorasLabora    = $item["iHorasLabora"]; // convertir int

            // Convertir y formatear los valores del JSON
            $cTipoIdentId    = isset($item["cTipoIdentId"])    ? trim($item["cTipoIdentId"]) : null;
            $cPersDocumento  = isset($item["cPersDocumento"])  ? trim($item["cPersDocumento"]) : null;
            $cPersPaterno    = isset($item["cPersPaterno"])    ? trim($item["cPersPaterno"]) : null;
            $cPersMaterno    = isset($item["cPersMaterno"])    ? trim($item["cPersMaterno"]) : null;
            $cPersNombre     = isset($item["cPersNombre"])     ? trim($item["cPersNombre"]) : null;
            $cPersSexo       = isset($item["cPersSexo"])       ? trim($item["cPersSexo"]) : null;

            //validar
            $servicio = $this->consultarDocumentoIdentidadService->buscar($cTipoIdentId, $cPersDocumento);



            // Convertir la fecha usando Carbon, si se proporciona
            if (isset($item["dPersNacimiento"]) && !empty($item["dPersNacimiento"])) {
                try {
                    $dPersNacimiento = Carbon::parse($item["dPersNacimiento"])->format('Y-m-d');
                } catch (\Exception $e) {
                    $dPersNacimiento = null;
                }
            } else {
                $dPersNacimiento = null;
            }

            // Convertir a entero
            $iHorasLabora    = isset($item["iHorasLabora"]) ? (int)$item["iHorasLabora"] : null;

            try {
                // Ejecutar el procedimiento almacenado pasando los parámetros en un array
                $query = DB::select("EXEC acad.SP_INS_ImportarPersonaDocenteIE ?,?,?,?,?,?,?,?,?,?", [
                    $cTipoIdentId,
                    $cPersDocumento,
                    $cPersPaterno,
                    $cPersMaterno,
                    $cPersNombre,
                    $cPersSexo,
                    $dPersNacimiento,
                    $iSedeId,
                    $iYAcadId,
                    $iHorasLabora
                ]);

                // Si la ejecución es exitosa, se guarda en 'procesados'
                $procesados[] = [
                    'validated' => true,
                    'message'   => 'Se obtuvo la información',
                    'data'      => $query,
                    'item'      => $item
                ];
            } catch (Exception $e) {
                // Si ocurre algún error, se guarda en 'observados'
                $observados[] = [
                    'validated' => false,
                    'message'   => $e->getMessage(),
                    'data'      => [], // Se puede enviar cualquier dato adicional
                    'item'      => $item
                ];
            }
        }

        // Construir la respuesta combinada
        $response = [
            'procesados' => $procesados,
            'observados' => $observados,
        ];

        // Determinar el código de estado: si hay algún error, se asigna 500; de lo contrario, 201
        $estado = (count($observados) > 0) ? 500 : 201;

        return new JsonResponse($response, $estado);
    }

    public function importarAmbiente_IE(Request $request)
    {
        $json   = $request->data;
        $iSedeId = $request->iSedeId;
        $iYAcadId = $request->iYAcadId;
        $iNivelTipoId = $request->iNivelTipoId;

        // Variables para almacenar resultados
        $procesados = [];
        $observados = [];



        foreach ($json as $item) {

            // Convertir y formatear los valores del JSON

            $TipoAmbienteId     = isset($item["TipoAmbienteId"])       ? trim($item["TipoAmbienteId"]) : null;
            $EstadoAmbId        = isset($item["EstadoAmbId"])       ? trim($item["EstadoAmbId"]) : null;
            $UbicaAmbId         = isset($item["UbicaAmbId"])       ? trim($item["UbicaAmbId"]) : null;
            $UsoAmbId           = isset($item["iUsoAmbId"])       ? trim($item["iUsoAmbId"]) : null;
            $PisoAmbid          = isset($item["PisoAmbid"])       ? trim($item["PisoAmbid"]) : null;
            $AmbienteEstado     = isset($item["AmbienteEstado"])       ? trim($item["AmbienteEstado"]) : null;
            $Turno              = isset($item["Turno"])       ? trim($item["Turno"]) : null;
            $Modalidad          = isset($item["Modalidad"])       ? trim($item["Modalidad"]) : null;
            $dni_tutor          = isset($item["dni_tutor"])       ? trim($item["dni_tutor"]) : null;
            $Grado              = isset($item["Grado"])       ? trim($item["Grado"]) : null;
            $Seccion            = isset($item["Seccion"])       ? trim($item["Seccion"]) : null;
            $AmbienteNombre     = $item["AmbienteNombre"];
            $AmbienteArea       = isset($item["AmbienteArea"])       ? trim($item["AmbienteArea"]) : null;
            $AmbienteAforo      = isset($item["AmbienteAforo"])       ? trim($item["AmbienteAforo"]) : null;
            $AmbienteObs        = isset($item["AmbienteObs"])       ? trim($item["AmbienteObs"]) : null;

            $msg = new ConsoleOutput();
            $msg->writeln("EXEC acad.SP_INS_ImportarAmbientesIE " . implode(",", [
               "'" . $TipoAmbienteId . "'",
               "'" . $EstadoAmbId . "'",
               "'" . $UbicaAmbId . "'",
               "'" . $UsoAmbId . "'",
               "'" . $PisoAmbid . "'",
               "'" . $AmbienteEstado . "'",
               "'" . $Turno . "'",
               "'" . $Modalidad . "'",
               "'" . $dni_tutor . "'",
               "'" . $Grado . "'",
               "'" . $Seccion . "'",
               "'" . $AmbienteNombre . "'",
               "'" . $AmbienteArea . "'",
               "'" . $AmbienteAforo . "'",
               "'" . $AmbienteObs . "'",

               "'" . $iSedeId . "'",
               "'" . $iYAcadId . "'",
               "'" . $iNivelTipoId . "'"
            ]));

            try {
                // Ejecutar el procedimiento almacenado pasando los parámetros en un array
                $query = DB::select("EXEC acad.SP_INS_ImportarAmbientesIE ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?", [
                    $TipoAmbienteId,
                    $EstadoAmbId,
                    $UbicaAmbId,
                    $UsoAmbId,
                    $PisoAmbid,
                    $AmbienteEstado,
                    $Turno,
                    $Modalidad,
                    $dni_tutor,
                    $Grado,
                    $Seccion,
                    $AmbienteNombre,
                    $AmbienteArea,
                    $AmbienteAforo,
                    $AmbienteObs,

                    $iSedeId,
                    $iYAcadId,
                    $iNivelTipoId
                ]);

                // Si la ejecución es exitosa, se guarda en 'procesados'
                $procesados[] = [
                    'validated' => true,
                    'message'   => 'Se obtuvo la información',
                    'data'      => $query,
                    'item'      => $item
                ];
            } catch (Exception $e) {
                // Si ocurre algún error, se guarda en 'observados'
                $observados[] = [
                    'validated' => false,
                    'message'   => $e->getMessage(),
                    'data'      => [], // Se puede enviar cualquier dato adicional
                    'item'      => $item
                ];
            }
        }

        // Construir la respuesta combinada
        $response = [
            'procesados' => $procesados,
            'observados' => $observados,
        ];

        // Determinar el código de estado: si hay algún error, se asigna 500; de lo contrario, 201
        $estado = (count($observados) > 0) ? 500 : 201;

        return new JsonResponse($response, $estado);
    }



    public function generarCredencialesIE(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'data' => 'required|array',
            'iSedeId' => 'required|integer',
            'iYAcadId' => 'required|integer',
            'iCredId' => 'required|integer',
            'condicion' => 'required|string',
        ]);

        $item = $request->data;
        $iSedeId = $request->iSedeId;
        $iYAcadId = $request->iYAcadId;
        $iCredId = $request->iCredId;
        $condicion = $request->condicion;

        $procesados = [];
        $observados = [];

        $perfilMapping = [
            1 => 4,    // DIRECTOR
            3 => 7,    // DOCENTE
            5 => 100,  // ASISTENCIA SOCIAL
            6 => 9     // AUXILIAR ASISTENCIA
        ];

        // Procesar cada item
        if (empty($condicion)) {
            $observados[] = ['validated' => false, 'message' => 'Falta la condición.', 'item' => $item];
            return new JsonResponse(['observados' => $observados], 400);
        }

        try {
            // Registrar nuevo personal si no existe
            if (empty($item["iPersId"])) {
                $iTipoPersId = ((INT)$item['iTipoIdentId'] == 2) ? 2 : 1;
                $parametros = [
                    $iTipoPersId,
                    $item['iTipoIdentId'],
                    $item['cPersDocumento'],
                    $item['cPersPaterno'],
                    $item['cPersMaterno'],
                    $item['cPersNombre'],
                    trim($item['cPersSexo']) ?: 'M',
                    null, //trim($item['dPersNacimiento']) ?: NULL,
                    NULL, // trim($item['iTipoEstCivId']) ?: NULL,
                    trim($item['cPersFotografia']) ?: NULL,
                    NULL, // trim($item['cPersRazonSocialNombre']) ?:
                    NULL, // cPersRazonSocialCorto
                    NULL, // cPersRazonSocialSigla
                    trim($item['cPersDomicilio']) ?: NULL,
                    $iCredId,
                    $item['iNacionId'],
                    trim($item['iPaisId']) ?: NULL,
                    trim($item['iDptoId']) ?: NULL,
                    trim($item['iPrvnId']) ?: NULL,
                    trim($item['iDsttId']) ?: NULL,
                ];

                $data = DB::select('execute grl.Sp_INS_personas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                $iPersId = !empty($data) ? $data[0]->iPersId : null;

                if ($iPersId) {
                    $procesados[] = ['validated' => true, 'message' => 'Nuevo personal registrado y credencial generada.', 'data' => $data, 'item' => $item];
                } else {
                    $observados[] = ['validated' => false, 'message' => 'Error al registrar el personal.', 'item' => $item];
                }
            } else {
                $iPersId = $item["iPersId"];
            }

            // Procesar según la condición
            if ($condicion === 'add_personal_ie') {
                if (is_null($iPersId) || is_null($item["iPersCargoId"]) || is_null($item["iYAcadId"]) || is_null($item["iSedeId"])) {
                    $observados[] = ['validated' => false, 'message' => 'Faltan parámetros requeridos.', 'item' => $item];
                } else {
                    $iPersCargoId = $item["iPersCargoId"];
                    $iHorasLabora = $item["iHorasLabora"] ?? 0;
                    $iPerfilId = $perfilMapping[$iPersCargoId] ?? 0;

                    $id = DB::table('acad.personal_ies')
                        ->where('iPersId', $iPersId)
                        ->where('iSedeId', $iSedeId)
                        ->where('iYAcadId', $iYAcadId)
                        ->value('id');

                    if ($id) {
                        $procesados[] = ['validated' => false, 'message' => 'Ya existe registro en Personal IE.', 'item' => $item];
                    } else {
                        $id = DB::table('acad.personal_ies')->insertGetId([
                            'iPersId' => $iPersId,
                            'iPersCargoId' => $iPersCargoId,
                            'iHorasLabora' => $iHorasLabora,
                            'iYAcadId' => $iYAcadId,
                            'iSedeId' => $iSedeId,
                        ]);
                        $procesados[] = ['validated' => true, 'message' => 'Se generó registro en Personal IE.', 'item' => $item];
                    }

                    $data = DB::select('execute seg.Sp_INS_credenciales_IE ?,?,?,?,?', [10, $iPersId, $iCredId, $iSedeId, $iPerfilId]);
                    $procesados[] = ['validated' => true, 'message' => 'Credencial generada.', 'data' => $data, 'item' => $item];
                }
            }

            if ($condicion === 'add_credencial_ie') {
                $iPerfilId = $request->iPerfilId;

                if (is_null($iPersId) || is_null($iCredId) || is_null($iPerfilId)) {
                    $observados[] = ['validated' => false, 'message' => 'Faltan parámetros requeridos.', 'item' => $item];
                } else {

                    $data = DB::select('execute seg.Sp_INS_credenciales_IE ?,?,?,?,?', [10, $iPersId, $iCredId, $iSedeId, $iPerfilId]);
                    $procesados[] = ['validated' => true, 'message' => 'Credencial generada.', 'data' => $data, 'item' => $item];
                }
            }
            if ($condicion === 'add_credencial') {

                if (is_null($iPersId) || is_null($iCredId) ) {
                    $observados[] = ['validated' => false, 'message' => 'Faltan parámetros requeridos.', 'item' => $item];
                } else {

                    $data = DB::select('execute seg.Sp_INS_credenciales ?,?,?', [10, $iPersId, $iCredId]);
                    $procesados[] = ['validated' => true, 'message' => 'Credencial generada.', 'data' => $data, 'item' => $item];
                }
            }
        } catch (\Exception $e) {
            $observados[] = ['validated' => false, 'message' => 'Error en base de datos: ' . $e->getMessage(), 'item' => $item];
        }

        // Construir la respuesta
        $response = [
            'procesados' => $procesados,
            'observados' => $observados,
        ];

        $estado = (count($observados) > 0) ? 500 : 201;
        return new JsonResponse($response, $estado);
    }


    ///// Procedimineto para cedenciales
    public function generarCredencialesIE_2(Request $request)
    {
        // Validar los datos de entrada
        $validatedData = $request->validate([
            'data' => 'required|array',
            'iSedeId' => 'required|integer',
            'iYAcadId' => 'required|integer',
            'iCredId' => 'required|integer',
            'condicion' => 'required|string',
        ]);

        $item = $validatedData['data'];
        $iSedeId = $validatedData['iSedeId'];
        $iYAcadId = $validatedData['iYAcadId'];
        $iCredId = $validatedData['iCredId'];
        $condicion = $validatedData['condicion'];

        $procesados = [];
        $observados = [];

        $perfilMapping = [
            1 => 4,    // DIRECTOR
            3 => 7,    // DOCENTE
            5 => 100,  // ASISTENCIA SOCIAL
            6 => 9     // AUXILIAR ASISTENCIA
        ];

        if (empty($condicion)) {
            return response()->json(['observados' => [['validated' => false, 'message' => 'Falta la condición.']]], 400);
        }

        DB::beginTransaction();
        try {
            // Registrar nuevo personal si no existe
            $iPersId = $item['iPersId'] ?? null;

            if (!$iPersId) {
                $iTipoPersId = ($item['iTipoIdentId'] == 2) ? 2 : 1;
                $parametros = [
                    $iTipoPersId, $item['iTipoIdentId'], $item['cPersDocumento'], $item['cPersPaterno'],
                    $item['cPersMaterno'], $item['cPersNombre'], $item['cPersSexo'] ?? null, $item['dPersNacimiento'] ?? null,
                    $item['iTipoEstCivId'] ?? null, $item['cPersFotografia'] ?? null, null, null, null,
                    $item['cPersDomicilio'] ?? null, $iCredId, $item['iNacionId'], $item['iPaisId'] ?? null,
                    $item['iDptoId'] ?? null, $item['iPrvnId'] ?? null, $item['iDsttId'] ?? null
                ];

                $data = DB::select('EXEC grl.Sp_INS_personas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                $iPersId = $data[0]->iPersId ?? null;

                if (!$iPersId) {
                    throw new \Exception('Error al registrar el personal.');
                }

                $procesados[] = ['validated' => true, 'message' => 'Nuevo personal registrado.', 'data' => $data];
            }

            // Registrar contacto si existe
            $this->registrarContacto($iPersId, $item['cPersEmail'] ?? null, 3, $iCredId, $observados);
            $this->registrarContacto($iPersId, $item['cTelefono'] ?? null, 2, $iCredId, $observados);

            // Procesar según la condición
            if ($condicion === 'add_personal_ie') {
                $this->registrarPersonalIE($iPersId, $item, $iSedeId, $iYAcadId, $iCredId, $perfilMapping, $procesados, $observados);
            } elseif ($condicion === 'add_credencial_ie') {
                $this->generarCredencialIE($iPersId, $iCredId, $iSedeId, $request->iPerfilId ?? null, $procesados, $observados);
            } elseif ($condicion === 'add_credencial') {
                $this->generarCredencial($iPersId, $iCredId, $procesados, $observados);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $observados[] = ['validated' => false, 'message' => 'Error en base de datos: ' . $e->getMessage()];
        }

        return response()->json(['procesados' => $procesados, 'observados' => $observados], count($observados) > 0 ? 500 : 201);
    }

    /**
     * Registrar contacto de la persona.
     */
    private function registrarContacto($iPersId, $contacto, $tipo, $iCredId, &$observados)
    {
        if (!empty($contacto)) {
            DB::select('EXEC grl.Sp_INS_personas_contactos ?,?,?,?,?', [$iPersId, $tipo, $contacto, 1, $iCredId]);
        } else {
            $observados[] = ['validated' => false, 'message' => 'Error al registrar el contacto.', 'tipo' => $tipo];
        }
    }

    /**
     * Registrar Personal en IE
     */
    private function registrarPersonalIE($iPersId, $item, $iSedeId, $iYAcadId, $iCredId, $perfilMapping, &$procesados, &$observados)
    {
        if (!$iPersId || empty($item["iPersCargoId"]) || !$iYAcadId || !$iSedeId) {
            $observados[] = ['validated' => false, 'message' => 'Faltan parámetros requeridos.'];
            return;
        }

        $iPersCargoId = $item["iPersCargoId"];
        $iHorasLabora = $item["iHorasLabora"] ?? 0;
        $iPerfilId = $perfilMapping[$iPersCargoId] ?? 0;

        $id = DB::table('acad.personal_ies')->where(compact('iPersId', 'iSedeId', 'iYAcadId'))->value('id');

        if (!$id) {
            DB::table('acad.personal_ies')->insert([
                'iPersId' => $iPersId,
                'iPersCargoId' => $iPersCargoId,
                'iHorasLabora' => $iHorasLabora,
                'iYAcadId' => $iYAcadId,
                'iSedeId' => $iSedeId,
            ]);
            $procesados[] = ['validated' => true, 'message' => 'Registro en Personal IE creado.'];
        }

        DB::select('EXEC seg.Sp_INS_credenciales_IE ?,?,?,?,?', [10, $iPersId, $iCredId, $iSedeId, $iPerfilId]);
    }

    /**
     * Generar credencial en IE
     */
    private function generarCredencialIE($iPersId, $iCredId, $iSedeId, $iPerfilId, &$procesados, &$observados)
    {
        if (!$iPersId || !$iCredId || !$iPerfilId) {
            $observados[] = ['validated' => false, 'message' => 'Faltan parámetros requeridos.'];
            return;
        }
        DB::select('EXEC seg.Sp_INS_credenciales_IE ?,?,?,?,?', [10, $iPersId, $iCredId, $iSedeId, $iPerfilId]);
        $procesados[] = ['validated' => true, 'message' => 'Credencial generada.'];
    }

    /**
     * Generar credencial
     */
    private function generarCredencial($iPersId, $iCredId, &$procesados, &$observados)
    {
        DB::select('EXEC seg.Sp_INS_credenciales ?,?,?', [10, $iPersId, $iCredId]);
        $procesados[] = ['validated' => true, 'message' => 'Credencial generada.'];
    }

    public function obtenerEstudiantesMatriculados(Request $request)
    {
      // \Log::info($request->all());
      $solicitud = [
        //30902,//
        (int) $request->iCredEntPerfId,
        (int)$request->iYAcadId,
        VerifyHash::decodesxId($request->iCursosNivelGradId),
        (int)$request->iSedeId,

       // 5 //(int)
    ];

        try {
            $data = DB::select('EXEC ere.Sp_SEL_obtenerEstudiantesMatriculados ?,?,?,?', $solicitud);
            $response = ['validated' => true, 'mensaje' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'mensaje' => $error_message];
            $codeResponse = 500;
        }

   // return response()->json($solicitud);
      return response()->json($response, $codeResponse);
    }
}






