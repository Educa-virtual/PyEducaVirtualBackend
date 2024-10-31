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

    public function guardarEvaluacion(Request $request)
    {
        $params = [
            $request->idTipoEvalId,
            $request->iNivelEvalId,
            $request->dtEvaluacionCreacion,
            $request->cEvaluacionNombre,
            $request->cEvaluacionDescripcion,
            $request->cEvaluacionUrlDrive,
            $request->cEvaluacionUrlPlantilla,
            $request->cEvaluacionUrlManual,
            $request->cEvaluacionUrlMatriz,
            $request->cEvaluacionObs,
            $request->dtEvaluacionLiberarMatriz,
            $request->dtEvaluacionLiberarCuadernillo,
            $request->dtEvaluacionLiberarResultados
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
    // Método para guardar las evaluaciones en la tabla de participantes
    public function guardarParticipacion(Request $request)
    {
        // Validación de los datos recibidos
        $items = $request->items;
        try {
            foreach ($items as $item) {
                DB::table('ere.iiee_participa_evaluaciones')->insert([
                    //'iIeeParticipaId' => $item['iIeeParticipaId'],
                    'iIieeId' => $item['iIieeId'],
                    'iEvaluacionId' => $item['iEvaluacionId'],
                ]);
            }

            return response()->json(['status' => 'success', 'message' => 'Datos guardados correctamente']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al guardar los datos', 'error' => $e->getMessage()], 500);
        }
    }

    // Método para eliminar las evaluaciones de la tabla de participantes
    // public function eliminarParticipacion(Request $request)
    // {
    //     // $id = $request->id;

    //     // // Validación de los datos recibidos
    //     // $items = $request->items;
    //     // try {
    //     //     foreach ($items as $item) {
    //     //         DB::table('ere.iiee_participa_evaluaciones')
    //     //             ->where('idEvaluacion', $item['idEvaluacion'])
    //     //             ->delete();
    //     //     }

    //     //     return response()->json(['status' => 'success', 'message' => 'Datos eliminados correctamente']);
    //     // } catch (Exception $e) {
    //     //     return response()->json(['status' => 'error', 'message' => 'Error al eliminar los datos', 'error' => $e->getMessage()], 500);
    //     // }
    //     // Obtener el ID del cuerpo de la solicitud
    //     $id = $request->id;
    //     // Validar que el ID sea proporcionado
    //     try {
    //         // Realizar la eliminación del registro
    //         $deleted = DB::table('ere.iiee_participa_evaluaciones')
    //             ->where('iIeeParticipaId', $id) // Cambia 'idEvaluacion' por el campo correcto según tu estructura
    //             ->delete();

    //         if ($deleted) {
    //             return response()->json(['status' => 'success', 'message' => 'Datos eliminados correctamente']);
    //         } else {
    //             return response()->json(['status' => 'error', 'message' => 'No se encontró el registro para eliminar'], 404);
    //         }
    //     } catch (Exception $e) {
    //         return response()->json(['status' => 'error', 'message' => 'Error al eliminar los datos', 'error' => $e->getMessage()], 500);
    //     }
    // }


    public function eliminarParticipacion($id)
    {
        $id = (int)$id; // Asegúrate de que sea un número 
        if ($id <= 0) {
            return response()->json(['status' => 'error', 'message' => 'ID inválido'], 400);
        }
        try {
            $deleted = DB::table('ere.iiee_participa_evaluaciones')
                ->where('iIieeId', $id)
                ->delete();

            if ($deleted) {
                return response()->json(['status' => 'success', 'message' => 'Datos eliminados correctamente']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'No se encontró el registro para eliminar'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al eliminar los datos', 'error' => $e->getMessage()], 500);
        }
    }


    public function actualizarEvaluacion(Request $request)
    {
        // Aquí agregas lógica para actualizar la evaluación usando el modelo Evaluacion
    }
}
