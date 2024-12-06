<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;

class ResultadoController extends Controller
{

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
    // public function getDatos(Request $request)
    // {
    //     // Validar los datos de entrada
    //     $request->validate([
    //         'tabla' => 'required|string',
    //         'campos' => 'nullable|string',
    //         'where' => 'nullable|string'
    //     ]);
    //     $esquema='dbo';
    //     $tabla = $request->input('tabla');
    //     $campos = $request->input('campos', '*'); // Usar todos los campos si no se especifica
    //     $where = $request->input('where', '1=1'); // Condición por defecto si no se proporciona
    //     //$where = addslashes($request->input('where', '1=1'));


    //     try {
    //         // Ejecutar el procedimiento almacenado usando DB::select
    //         // $results = DB::select("EXEC nombre_sp @table_name = ?, @where_condition = ?", [$tabla, $where]);
    //         $results = DB::select('EXEC SP_SEL_DesdeTablaOVista
    //                 @nombreEsquema = ?, @nombreObjeto = ?,@campos = ?, @condicionWhere=?',
    //                 [$esquema, $tabla,$campos, $where]);
    //         // Retornar los datos en formato JSON
    //         return response()->json([
    //             'status' => 'success',
    //            'data' => $results ?: [] // Asegurarse de que 'results' sea siempre un arreglo
    //         ]);
    //     } catch (\Exception $e) {
    //         // Manejar cualquier error y retornar un mensaje adecuado
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Error al ejecutar la consulta: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }
    // public function guardarDatos(Request $request){

    //     $esquema='dbo';
    //     // Validar los datos recibidos
    //     $request->validate([
    //         'tabla' => 'required|string',
    //         'datos' => 'required|array',
    //         'where' => 'required|array',
    //     ]);

    //     $tabla = $request->input('tabla');
    //     $datos = $request->input('datos'); // Datos que se actualizarán
    //     $where = $request->input('where'); // Condición WHERE opcional

    //     // Convertir datos a JSON si se requiere por el procedimiento almacenado
    //     $datosJson = json_encode($datos);
    //     $whereJson = json_encode($where);

    //     try {
    //         // Ejecutar el procedimiento almacenado usando DB::select
    //         $results = DB::select('EXEC SP_UPD_EnTablaConJSON
    //                 @Esquema = ?, @Tabla = ?,@DatosJSON = ?, @CondicionesJSON = ?',
    //                 [$esquema, $tabla, $datosJson, $whereJson]);
    //         // Retornar los datos en formato JSON
    //         return response()->json([
    //             'status' => 'success',
    //             'data' => $results
    //         ]);
    //     } catch (\Exception $e) {
    //         // Manejar cualquier error y retornar un mensaje adecuado
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Error al actualizar: ' . $e->getMessage()
    //         ], 500);
    //     }

    // }

}
