<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Dompdf\Options;

class ResultadoController extends Controller
{
    //private apiUrl = 'http://localhost:8000/api'; // Backend URL
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function obtenerResultados(Request $request){

        //return $request->all();
        //return 1;
        $request->validate([
            'idDocCursoId' => 'required|integer',
            'iEstudianteId' => 'required|integer',
        ]);

        $idDocCursoId = $request->idDocCursoId;
        $iEstudianteId = $request->iEstudianteId;
        
        $params =[
            $idDocCursoId,
            $iEstudianteId
        ];
        //return $params;
        try {
            $data = DB ::select('EXEC aula.SP_SEL_listarActividadForoXiEstudianteId ?,?', $params);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = 200;

            return $response;
        } 
        catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $estado = 500;
        }

        return new JsonResponse($response,$estado);
    }
    public function guardarCalfcEstudiante(Request $request){        
        //$esquema='grl';
        // Validar los datos recibidos
        $request->validate([
            'esquema' => 'required|string',
            'tabla' => 'required|string',
            'datos' => 'required|array',
            'where' => 'required|array',
        ]);
        
        $esquema = $request->input('esquema');
        $tabla = $request->input('tabla');
        $datos = $request->input('datos'); // Datos que se actualizarán
        $where = $request->input('where'); // Condición WHERE opcional

        // Convertir datos a JSON si se requiere por el procedimiento almacenado
        $datosJson = json_encode($datos);
        $whereJson = json_encode($where);
       try { 
            // Ejecutar el procedimiento almacenado usando DB::select
            $results = DB::select('EXEC grl.SP_UPD_EnTablaConJSON
                    @Esquema = ?, @Tabla = ?,@DatosJSON = ?, @CondicionesJSON = ?',
                    [$esquema, $tabla, $datosJson, $whereJson]);
            
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $results];
            $estado = 200;

            return $response;
           
       }       
       catch (\Exception $e) {
        $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
        $estado = 500;
        }
        return new JsonResponse($response,$estado);

    }
    public function obtenerCalificacionesFinalesReporte(Request $request){
        // Validar los datos de entrada
        $request->validate([
            'tabla' => 'required|string',
            'campos' => 'nullable|string',
            'where' => 'nullable|string'
        ]);
        $esquema='acad';
        $tabla = $request->input('tabla');
        $campos = $request->input('campos', '*'); // Usar todos los campos si no se especifica
        $where = $request->input('where', '1=1'); // Condición por defecto si no se proporciona
        //$where = addslashes($request->input('where', '1=1'));
        try { 
            // Ejecutar el procedimiento almacenado usando DB::select
            $data = DB::select('EXEC grl.SP_SEL_DesdeTablaOVista
                        @nombreEsquema = ?, @nombreObjeto = ?,@campos = ?, @condicionWhere=?',
                        [$esquema, $tabla,$campos, $where]);
            
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = 200;

            return $response;
           
       }       
       catch (\Exception $e) {
        $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
        $estado = 500;
        }
        return new JsonResponse($response,$estado);
    }
    public function habilitarCalificacion(Request $request){
        //return 1;
        try {
            $data = DB ::select('EXEC acad.SP_SEL_obtenerPeriodosEvaluacion ?,?',[$request->iYAcadId,$request->iCredId]);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = 200;

            return $response;
        }
        catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $estado = 500;
        }

        return new JsonResponse($response,$estado);
    }
    public function obtenerReporteFinalNotas (Request $request){
       // Validación de los parámetros de entrada
        $request->validate([
            'iIeCursoId' => 'required | string ', 
        ]);
        // return $request->iCursoId;
        $iCursoId = $request->iIeCursoId;
        // Si se pasa un valor para iCursoId, decodificarlo
        if ($request->iIeCursoId) {
            $iCursoId = $this->hashids->decode($iCursoId);             
            $iCursoId = count($iCursoId) > 0 ? $iCursoId[0] : $iCursoId;
        }
        try {
            // Ejecutar el procedimiento almacenado
            $data = DB::select('EXEC [acad].[Sp_SEL_reporteFinalDeNotas] ?', [$iCursoId]);
            // Preparar la respuesta
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = 200;

            return $response;
        } catch (\Exception $e) {
            // Manejo de excepción y respuesta de error
            $response = [
                'validated' => false,
                'message' => $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine(),
                'data' => [],
            ];
            $estado = 500;
            return new JsonResponse($response, $estado);
        }
        
    }
    public function reporteDeLogros(){
        // Validación de los parámetros de entrada
        // $request->validate([
        //     'iIeCursoId' => 'required | string ', 
        // ]);
         //return $request->iCursoId;
        $iCursoId = 1;// $request->iIeCursoId;
        // Si se pasa un valor para iCursoId, decodificarlo
        // if ($request->iIeCursoId) {
        //     $iCursoId = $this->hashids->decode($iCursoId);             
        //     $iCursoId = count($iCursoId) > 0 ? $iCursoId[0] : $iCursoId;
        // }

        //$cPersNombreLargo = "Docente";
        //CARGAR LOGOS 
        $imagePath = public_path('images\logo_IE\dremo.jpg');
        $imageData = base64_encode(file_get_contents($imagePath));
        $region = 'data:image/jpeg;base64,' . $imageData;
    
        $imagePath = public_path('images\logo_IE\juan_XXIII.jpg');
        $imageData = base64_encode(file_get_contents($imagePath));
        $insignia = 'data:image/jpeg;base64,' . $imageData;
        //'data:image/jpeg;base64,' . $imageData;
    
        $imagePath = public_path('images\logo_IE\Logo-buho.jpg');
        $imageData = base64_encode(file_get_contents($imagePath));
        $virtual = 'data:image/jpeg;base64,' . $imageData;


        $data = DB::select('EXEC acad.Sp_SEL_reporteFinalDeNotas ?', [$iCursoId]);
            
            $datos = [];
            foreach ($data as $key => $pregunta) {
                
                // Si pasa los filtros, agregar la pregunta a los datos
                $datos['preguntas'][$key] = [
                    'completoalumno' => $pregunta->completoalumno,
                    'Trimestre_I' => $pregunta->iEscalaCalifIdPeriodo1,
                    'Trimestre_II' => $pregunta->iEscalaCalifIdPeriodo2,
                    'Trimestre_III' => $pregunta->iEscalaCalifIdPeriodo3,
                    'Trimestre_IV' => $pregunta->iEscalaCalifIdPeriodo4,
                    'Conclusion_descriptiva' => $pregunta->cDetMatConclusionDescPromedio,
                 
                   
                    
                    // 'evaluacion_descripcion' => $pregunta->cEvaluacionDescripcion,
                    // 'competencia_nombre' => $pregunta->cCompetenciaNombre,
                ];
            }
            $data = [
                
                'preguntas' => $datos['preguntas'],
                "imageLogo" => $region,// Ruta absoluta
                "logoVirtual" => $virtual,// Ruta absoluta
                "logoInsignia" => $insignia,// Ruta absoluta
                "cPersNombreLargo" =>$pregunta->completoalumno,
            ];
            //return $data;

            $pdf = PDF::loadView('aula.nivelDeLogrosReporte', $data)
                ->setPaper('a4', 'landscape')
                ->stream('reporteLogro.pdf');

            return $pdf;
        // try{
        //     $data = DB::select('EXEC acad.Sp_SEL_reporteFinalDeNotas ?', [$iCursoId]);
            
        //     $datos = [];
        //     foreach ($data as $key => $pregunta) {
                
        //         // Si pasa los filtros, agregar la pregunta a los datos
        //         $datos['preguntas'][$key] = [
        //             'completoalumno' => $pregunta->completoalumno,
        //             'Trimestre_I' => $pregunta->iEscalaCalifIdPeriodo1,
        //             'Trimestre_II' => $pregunta->iEscalaCalifIdPeriodo2,
        //             'Trimestre_III' => $pregunta->iEscalaCalifIdPeriodo3,
        //             'Trimestre_IV' => $pregunta->iEscalaCalifIdPeriodo4,
        //             'Conclusion_descriptiva' => $pregunta->cDetMatConclusionDescPromedio,
        //             // 'evaluacion_descripcion' => $pregunta->cEvaluacionDescripcion,
        //             // 'competencia_nombre' => $pregunta->cCompetenciaNombre,
        //         ];
        //     }
        //     $data = [
                
        //         'preguntas' => $datos['preguntas'],
        //     ];
        //     //return $data;

        //     $pdf = PDF::loadView('aula.nivelDeLogrosReporte', $data)
        //         ->setPaper('a4', 'landscape')
        //         ->stream('reporteLogro.pdf');

        //     return $pdf;
        // }catch(\Exception $e) {
        //             return response()->json([
        //                 'validated' => false,
        //                 'message' => 'Error al generar el reporte: ' . $e->getMessage(),
        //             ], 500);
        // }
        

        
           
       
        // return new JsonResponse($response,$estado);
    
        // try {
        //     // Obtener los datos del procedimiento almacenado
        //     $data = DB::select('EXEC acad.Sp_SEL_reporteFinalDeNotas');
    
        //     // Verifica que haya datos antes de generar el PDF
        //     // if (empty($data)) {
        //     //     return response()->json([
        //     //         'validated' => false,
        //     //         'message' => 'No hay datos disponibles para generar el reporte.',
        //     //     ], 404);
        //     // }
        //     $pdf = PDF::loadView('aula.nivelDeLogroReporte', $data)
        //         ->setPaper('a4', 'landscape')
        //         ->stream('reporteLogro.pdf');

        //     return $pdf;
    
        //     // Generar el PDF
        //     // $pdf = PDF::loadView('aula.nivelDeLogroReporte', ['data' => $data])
        //     //     ->setPaper('a4', 'landscape');
    
        //     // // Retornar el PDF como respuesta
        //     // return $pdf->stream('reporteLogro.pdf');
    
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'validated' => false,
        //         'message' => 'Error al generar el reporte: ' . $e->getMessage(),
        //     ], 500);
        // }
    }
    public function reporteDeLogroFinalXAño(){
        $iIeCursoId = 1;
        $iSeccionId = 2;
        $iYAcadId = 3;
        $params = [
            $iIeCursoId,
            $iSeccionId,
            $iYAcadId
        ];
        
        try {
            // Ejecutar el procedimiento almacenado
            $data = DB::select('EXEC [aula].[SP_SEL_listarEstudiantesCursoSeccionYAcad] ?,?,?', $params);
            // Preparar la respuesta
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = 200;

            return $response;
        } catch (\Exception $e) {
            // Manejo de excepción y respuesta de error
            $response = [
                'validated' => false,
                'message' => $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine(),
                'data' => [],
            ];
            $estado = 500;
            return new JsonResponse($response, $estado);
        }
    }
}
