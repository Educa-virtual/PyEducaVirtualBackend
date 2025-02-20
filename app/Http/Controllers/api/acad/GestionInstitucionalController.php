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

class GestionInstitucionalController extends Controller
{
    
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
}



