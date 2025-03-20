<?php

namespace App\Http\Controllers\api\acad;

use App\Http\Controllers\Controller;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;
use Carbon\Carbon; // Agrega esta línea para importar Carbon

use App\Services\ConsultarDocumentoIdentidadService;

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

            IF($cTipoIdentId  === 'DNI'){
                $servicio = $this->consultarDocumentoIdentidadService->buscar($request->iTipoIdentId, $request->cPersDocumento);
            }
           

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
            $UbicaAmbId         = isset($item["UbicaAmbId "])       ? trim($item["UbicaAmbId "]) : null;
            $UsoAmbId           = isset($item["iUsoAmbId "])       ? trim($item["iUsoAmbId "]) : null; 
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
        $json     = $request->data;
        $iSedeId  = $request->iSedeId;
        $iYAcadId = $request->iYAcadId;

        $procesados = [];
        $observados = [];

        // Obtener todos los iPersId existentes en una sola consulta
        $idsExistentes = DB::table('acad.personal_ies')
            ->where('iSedeId', $iSedeId)
            ->where('iYAcadId', $iYAcadId)
            ->pluck('iPersId')
            ->toArray();

        // Función auxiliar para registrar credenciales
        $registrarCredencial = function ($iPersId, $iCredSesionId, $iSedeId, $iPerfilId) {
            return DB::select("EXEC seg.Sp_INS_credenciales_IE ?,?,?,?,?", [
                10, $iPersId, $iCredSesionId, $iSedeId, $iPerfilId
            ]);
        };

        $perfilMapping = [
            1 => 4,    // DIRECTOR
            3 => 7,    // DOCENTE
            5 => 100,  // ASISTENCIA SOCIAL
            6 => 9     // AUXILIAR ASISTENCIA
        ];

        foreach ($json as $item) {
            if (empty($item["condicion"])) {
                $observados[] = ['validated' => false, 'message' => 'Falta la condición.', 'item' => $item];
                continue;
            }

            $condicion = $item["condicion"];

            try {
                if ($condicion === 'add_personal_ie') {
                    if (empty($item["iPersId"]) || empty($item["iPersCargoId"]) || empty($item["iCredSesionId"])) {
                        $observados[] = ['validated' => false, 'message' => 'Faltan parámetros requeridos.', 'item' => $item];
                        continue;
                    }

                    $iPersId = $item["iPersId"];
                    $iCredSesionId = $item["iCredSesionId"];
                    $iPersCargoId = $item["iPersCargoId"];
                    $iHorasLabora = $item["iHorasLabora"] ?? 0;
                    $iPerfilId = $perfilMapping[$iPersCargoId] ?? 0;

                    if (in_array($iPersId, $idsExistentes)) {
                        if ($iPerfilId > 0) {
                            $query = $registrarCredencial($iPersId, $iCredSesionId, $iSedeId, $iPerfilId);
                            $procesados[] = ['validated' => true, 'message' => 'Credencial generada para personal existente.', 'data' => $query, 'item' => $item];
                        }
                        continue;
                    }

                    $id = DB::table('acad.personal_ies')->insertGetId([
                        'iPersId'      => $iPersId,
                        'iPersCargoId' => $iPersCargoId,
                        'iHorasLabora' => $iHorasLabora,
                        'iYAcadId'     => $iYAcadId,
                        'iSedeId'      => $iSedeId
                    ]);

                    if ($id && $iPerfilId > 0) {
                        $query = $registrarCredencial($iPersId, $iCredSesionId, $iSedeId, $iPerfilId);
                        $procesados[] = ['validated' => true, 'message' => 'Nuevo personal registrado y credencial generada.', 'data' => $query, 'item' => $item];
                    } else {
                        $procesados[] = ['validated' => true, 'message' => 'Nuevo personal registrado sin credencial.', 'item' => $item];
                    }
                }

                if ($condicion === 'add_credencial_ie') {
                    if (empty($item["iPersId"]) || empty($item["iCredSesionId"]) || empty($item["iPerfilId"])) {
                        $observados[] = ['validated' => false, 'message' => 'Faltan parámetros requeridos.', 'item' => $item];
                        continue;
                    }

                    $iPersId = $item["iPersId"];
                    $iCredSesionId = $item["iCredSesionId"];
                    $iPerfilId = $item["iPerfilId"];

                    if (in_array($iPersId, $idsExistentes)) {
                        $observados[] = ['validated' => false, 'message' => 'Ya existe credencial y perfil asignado para usuario.', 'item' => $item];
                        continue;
                    }

                    $query = $registrarCredencial($iPersId, $iCredSesionId, $iSedeId, $iPerfilId);
                    $procesados[] = ['validated' => true, 'message' => 'Nuevo personal registrado y credencial generada.', 'data' => $query, 'item' => $item];
                }
            } catch (QueryException $e) {
                $observados[] = ['validated' => false, 'message' => 'Error en base de datos: ' . $e->getMessage(), 'item' => $item];
            }
        }

        $response = ['procesados' => $procesados, 'observados' => $observados];
        $estado = count($observados) > 0 ? 500 : 201;

        return new JsonResponse($response, $estado);
    }

}



