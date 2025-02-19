<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class AcademicoController extends Controller
{
    public function obtenerDatos(Request $request){
        $documento = $request->cPersDocumento;
        $iIieeId = $request->iIieeId;
        
        $solicitud = [
            $documento,
            $iIieeId
        ];
    
        try {
            $data = DB::select('EXEC aula.SP_SEL_academico ?,?', $solicitud);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = 200;
        } catch (Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $estado = 500;
        }
        return new JsonResponse($response, $estado);
    }
    public function obtenerAcademicoGrado(Request $request){
        $iiee = $request->iIieeId;
        $iGrado = $request->iGrado;
        $iYear = $request->iYear;

        $solicitud = [
            $iiee,
            $iGrado,
            $iYear, 
        ];        
        
        try {
            $data = DB::select('EXEC aula.SP_SEL_academicoGrado ?,?,?', $solicitud);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = 200;
        } catch (Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $estado = 500;
        }
        return new JsonResponse($response, $estado);
        
    }
    public function reporte(Request $request){
        
        // $documento = '41789603';
        $documento = $request->cPersDocumento;
        $iiee = $request->iIieeId;
        $solicitud = [
            $documento,
            $iiee,
        ];
        $data = DB::select('EXEC aula.SP_SEL_academico ?,?', $solicitud);
        
        $columna = [];
        $fila = [];
        $historial = $data[0]->historial;
        $detalle = $data[0]->detalle;
        $json_detalle = json_decode($detalle,true);
        $json_hisotiral = json_decode($historial,true);
       
        foreach($json_hisotiral as $datos){

            $area = array_filter($fila ,function($box) use ($datos){
                return  $box["cCursoNombre"] == $datos["cCursoNombre"];
            });
    
            if (empty($area)) {
                $fila[]=[
                    "cCursoNombre"=>$datos["cCursoNombre"],
                    "nota"=>[
                        0=>[
                            "promedio"=>$datos["nDetMatrPromedio"],
                            "grado"=>$datos["cGradoAbreviacion"],
                            "year"=>$datos["cYAcadNombre"]
                        ]
                    ]    
                ];
            }else{
                foreach($fila as &$fl){
                    if($fl["cCursoNombre"]==$datos["cCursoNombre"]){
                        $fl["nota"][]=[
                            "promedio"=>$datos["nDetMatrPromedio"],
                            "grado"=>$datos["cGradoAbreviacion"],
                            "year"=>$datos["cYAcadNombre"]
                        ];
                    }
                }
            }
            
           
            $encabezado = array_filter($columna,function($box) use ($datos){
                return $box["cGradoAbreviacion"] == $datos["cGradoAbreviacion"];
            });
            
            if(empty($encabezado)) {
                $columna[]=[
                    "cGradoAbreviacion"=>$datos["cGradoAbreviacion"],
                    "cYAcadNombre"=>$datos["cYAcadNombre"]
                ];
            }
        }
       
        $logo = $json_detalle[0]["cIieeLogo"];
        $verLogo = explode(",",$logo);
        $base64Image = str_replace(["\r", "\n"], '', $verLogo[1]);
        if (base64_decode($base64Image, true) === false) {
            $logo="iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=";
        }
        $respuesta = [
            "iiee"=>$json_detalle[0]["cIieeNombre"],
            "columna"=>$columna,
            "fila"=>$fila,
            "codigo"=>$json_detalle[0]["cIieeCodigoModular"],
            "logo"=>$logo,
            "estudiante"=>$data[0]->cEstNombres." ".$data[0]->cEstPaterno." ".$data[0]->cEstMaterno,
            "nivel"=>$json_detalle[0]["cNivelTipoNombre"],
            "distrito"=>$json_detalle[0]["cDsttNombre"],
            "provincia"=>$json_detalle[0]["cPrvnNombre"],
            "departamento"=>$json_detalle[0]["cDptoNombre"],
        ];

        $pdf = PDF::loadView('administracion.academico_reporte', $respuesta)
        ->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true])
        ->setPaper('a4', 'landscape')
        ->stream('reporte.pdf');
        return $pdf;
    }
    public function reporteGrado(Request $request){
        
        // $documento = '41789603';
        $alumnno = $request->alumnno;
        $curso = $request->curso;

        print_r($alumnno);

        // $solicitud = [
        //     $documento,
        //     $iiee,
        // ];
        // $data = DB::select('EXEC aula.SP_SEL_academico ?,?', $solicitud);
        
        // $columna = [];
        // $fila = [];
        // $historial = $data[0]->historial;
        // $detalle = $data[0]->detalle;
        // $json_detalle = json_decode($detalle,true);
        // $json_hisotiral = json_decode($historial,true);
       
        // foreach($json_hisotiral as $datos){

        //     $area = array_filter($fila ,function($box) use ($datos){
        //         return  $box["cCursoNombre"] == $datos["cCursoNombre"];
        //     });
    
        //     if (empty($area)) {
        //         $fila[]=[
        //             "cCursoNombre"=>$datos["cCursoNombre"],
        //             "nota"=>[
        //                 0=>[
        //                     "promedio"=>$datos["nDetMatrPromedio"],
        //                     "grado"=>$datos["cGradoAbreviacion"],
        //                     "year"=>$datos["cYAcadNombre"]
        //                 ]
        //             ]    
        //         ];
        //     }else{
        //         foreach($fila as &$fl){
        //             if($fl["cCursoNombre"]==$datos["cCursoNombre"]){
        //                 $fl["nota"][]=[
        //                     "promedio"=>$datos["nDetMatrPromedio"],
        //                     "grado"=>$datos["cGradoAbreviacion"],
        //                     "year"=>$datos["cYAcadNombre"]
        //                 ];
        //             }
        //         }
        //     }
            
           
        //     $encabezado = array_filter($columna,function($box) use ($datos){
        //         return $box["cGradoAbreviacion"] == $datos["cGradoAbreviacion"];
        //     });
            
        //     if(empty($encabezado)) {
        //         $columna[]=[
        //             "cGradoAbreviacion"=>$datos["cGradoAbreviacion"],
        //             "cYAcadNombre"=>$datos["cYAcadNombre"]
        //         ];
        //     }
        // }
       
        // $logo = $json_detalle[0]["cIieeLogo"];
        // $verLogo = explode(",",$logo);
        // $base64Image = str_replace(["\r", "\n"], '', $verLogo[1]);
        // if (base64_decode($base64Image, true) === false) {
        //     $logo="iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=";
        // }
        // $respuesta = [
        //     "iiee"=>$json_detalle[0]["cIieeNombre"],
        //     "columna"=>$columna,
        //     "fila"=>$fila,
        //     "codigo"=>$json_detalle[0]["cIieeCodigoModular"],
        //     "logo"=>$logo,
        //     "estudiante"=>$data[0]->cEstNombres." ".$data[0]->cEstPaterno." ".$data[0]->cEstMaterno,
        //     "nivel"=>$json_detalle[0]["cNivelTipoNombre"],
        //     "distrito"=>$json_detalle[0]["cDsttNombre"],
        //     "provincia"=>$json_detalle[0]["cPrvnNombre"],
        //     "departamento"=>$json_detalle[0]["cDptoNombre"],
        // ];

        // $pdf = PDF::loadView('administracion.academico_reporte', $respuesta)
        // ->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true])
        // ->setPaper('a4', 'landscape')
        // ->stream('reporte.pdf');
        // return $pdf;
    }
}
