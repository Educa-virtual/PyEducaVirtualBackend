<?php

namespace App\Http\Controllers\Ere;


use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use App\Models\Ere\ereEvaluacion; // Importa tu modelo aquí
use App\Models\Ere\EreEvaluacion;
use Carbon\Carbon;

class EvaluacionesController extends ApiController
{
    public function obtenerEvaluaciones()
    {

        $campos = 'iEvaluacionId,idTipoEvalId,iNivelEvalId,dtEvaluacionCreacion,cEvaluacionNombre,cEvaluacionDescripcion,cEvaluacionUrlDrive,cEvaluacionUrlPlantilla,cEvaluacionUrlManual,cEvaluacionUrlMatriz,cEvaluacionObs,dtEvaluacionLiberarMatriz,dtEvaluacionLiberarCuadernillo,dtEvaluacionLiberarResultados';

        $where = '';


        $params = [
            'ere',
            'vistaInstitucionEducativa',
            $campos,
            $where

        ];


        try {
            $evaluaciones = DB::select('EXEC ere.sp_SEL_Evaluaciones');
            return $this->successResponse(
                $evaluaciones,
                'Datos obtenidos correctamente'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al obtener los datos');
        }
    }
    // public function actualizarEvaluacion(Request $request)
    // {
    //     return  $this->errorResponse(null, 'Error al obtener los datos');

    //     /*return $this->successResponse(
    //         null,
    //         'Datos obtenidos correctamente'
    //     );*/
    // }

    // Método para definir el formato de fecha
    public function getDateFormat()
    {
        return 'Y-d-m'; // Establece el formato de fecha deseado
    }
    
    public function guardarEvaluacion(Request $request)
    {
               
            $params = [
                // $iEvaluacionId='16',
                // $idTipoEvalId='1',
                // $iNivelEvalId='1',
                // $dtEvaluacionCreacion='2024-10-27','','','','','','','','',''
                //$dtEvaluacionCreacion='2024-10-27'

                //$request->iEvaluacionId,
                $request->idTipoEvalId,
                $request->iNivelEvalId,
                '','','','','','','','','','',''

            // //    Carbon::createFromFormat('Y-m-d', $request->dtEvaluacionCreacion),'','','','','','','','','',''
            //     $dtEvaluacionCreacion, // Solo la fecha
            //     //$request->dtEvaluacionLiberarMatriz,
            //     '','','','','','','','','',''

                // $request->cEvaluacionNombre,
                // $request->cEvaluacionDescripcion,
                // $request->cEvaluacionUrlDrive,
                // $request->cEvaluacionUrlPlantilla,
                // $request->cEvaluacionUrlManual,
                // $request->cEvaluacionUrlMatriz,
                // $request->cEvaluacionObs,
                // $request->dtEvaluacionLiberarMatriz,
                // $request->dtEvaluacionLiberarCuadernillo,
                // $request->dtEvaluacionLiberarResultados
            ];

        try {
            // Llama al método del modelo que ejecuta el procedimiento almacenado
            $evaluaciones = EreEvaluacion::guardarEvaluaciones($params);


            // Suponiendo que guardarEvaluaciones() retorna el ID generado
            $iEvaluacionId = $evaluacion->iEvaluacionId ?? null;

            return response()->json([
                'status' => 'Success',
                'data' => $evaluaciones,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'Error',
                'message' => 'Error al obtener los datos Porque',
                'data' => [
                    'errorInfo' => $e->getMessage(),
                ],
            ], 500);
        }
    }
    

    public function obtenerUltimaEvaluacion()
    {
        try {
            // Realiza la consulta a la tabla 'evaluacion'
            $ultimaEvaluacion = DB::table('ere.evaluacion')
                ->orderBy('iEvaluacionId', 'desc')
                ->first();

            return response()->json(['data' => $ultimaEvaluacion ? [$ultimaEvaluacion] : []]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener los datos', 'message' => $e->getMessage()], 500);
        }
    }
    // public function obtenerEvaluaciones()
    // {
    //     //ESTE CODIGO ES CON EL PROCEDIMIENTO ALMACENADO: PROCEDIMIENTO
    //     try {
    //         // Llama al método del modelo que ejecuta el procedimiento almacenado
    //         $evaluaciones = EreEvaluacion::obtenerEvaluaciones();

    //         return response()->json([
    //             'status' => 'Success',
    //             'data' => $evaluaciones,
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'Error',
    //             'message' => 'Error al obtener los datos',
    //             'data' => [
    //                 'errorInfo' => $e->getMessage(),
    //             ],
    //         ], 500);
    //     }
    //     //ESTE CODIGO ES DIRECTO A LA TABLA: TABLE
    //     // try {
    //     //     // Obtén todas las evaluaciones de la tabla 'evaluacion'
    //     //     $evaluaciones = EreEvaluacion::all(); // O usa EreEvaluacion::get() para obtener colecciones

    //     //     return response()->json([
    //     //         'status' => 'Success',
    //     //         'data' => $evaluaciones,
    //     //     ]);
    //     // } catch (\Exception $e) {
    //     //     return response()->json([
    //     //         'status' => 'Error',
    //     //         'message' => 'Error al obtener los datos',
    //     //         'data' => [
    //     //             'errorInfo' => $e->getMessage(),
    //     //         ],
    //     //     ], 500);
    //     // }
    // }
    public function actualizarEvaluacion(Request $request)
    {
        // Aquí agregas lógica para actualizar la evaluación usando el modelo Evaluacion
    }
}
