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
                    'iIieeId' => $item['iIieeId'],
                    'iEvaluacionId' => $item['iEvaluacionId'],
                ]);
            }
            return response()->json(['status' => 'success', 'message' => 'Datos guardados correctamente']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al guardar los datos', 'error' => $e->getMessage()], 500);
        }
    }
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

    public function actualizarEvaluacion(Request $request, $iEvaluacionId)
    {
        // Validar solo los campos opcionales
        $request->validate([
            'idTipoEvalId' => 'nullable|integer',
            'iNivelEvalId' => 'nullable|integer',
            'dtEvaluacionCreacion' => 'nullable|string',
            'cEvaluacionNombre' => 'nullable|string|max:255',
            'cEvaluacionDescripcion' => 'nullable|string|max:255',
            'cEvaluacionUrlDrive' => 'nullable|string|max:255',
            'cEvaluacionUrlPlantilla' => 'nullable|string|max:255',
            'cEvaluacionUrlManual' => 'nullable|string|max:255',
            'cEvaluacionUrlMatriz' => 'nullable|string|max:255',
            'cEvaluacionObs' => 'nullable|string|max:255',
            'dtEvaluacionLiberarMatriz' => 'nullable|string',
            'dtEvaluacionLiberarCuadernillo' => 'nullable|string',
            'dtEvaluacionLiberarResultados' => 'nullable|string'
        ]);
        // Preparar los valores para la llamada al procedimiento
        $params = [
            'iEvaluacionId' => $iEvaluacionId,
            'idTipoEvalId' => $request->input('idTipoEvalId', null),
            'iNivelEvalId' => $request->input('iNivelEvalId', null),
            'dtEvaluacionCreacion' => $request->input('dtEvaluacionCreacion', null),
            'cEvaluacionNombre' => $request->input('cEvaluacionNombre', null),
            'cEvaluacionDescripcion' => $request->input('cEvaluacionDescripcion', null),
            'cEvaluacionUrlDrive' => $request->input('cEvaluacionUrlDrive', null),
            'cEvaluacionUrlPlantilla' => $request->input('cEvaluacionUrlPlantilla', null),
            'cEvaluacionUrlManual' => $request->input('cEvaluacionUrlManual', null),
            'cEvaluacionUrlMatriz' => $request->input('cEvaluacionUrlMatriz', null),
            'cEvaluacionObs' => $request->input('cEvaluacionObs', null),
            'dtEvaluacionLiberarMatriz' => $request->input('dtEvaluacionLiberarMatriz', null),
            'dtEvaluacionLiberarCuadernillo' => $request->input('dtEvaluacionLiberarCuadernillo', null),
            'dtEvaluacionLiberarResultados' => $request->input('dtEvaluacionLiberarResultados', null)
        ];

        // Construir la llamada dinámica al procedimiento
        DB::statement('EXEC ere.sp_UPD_Evaluaciones 
        @iEvaluacionId = :iEvaluacionId, 
        @idTipoEvalId = :idTipoEvalId, 
        @iNivelEvalId = :iNivelEvalId, 
        @dtEvaluacionCreacion = :dtEvaluacionCreacion, 
        @cEvaluacionNombre = :cEvaluacionNombre, 
        @cEvaluacionDescripcion = :cEvaluacionDescripcion, 
        @cEvaluacionUrlDrive = :cEvaluacionUrlDrive, 
        @cEvaluacionUrlPlantilla = :cEvaluacionUrlPlantilla, 
        @cEvaluacionUrlManual = :cEvaluacionUrlManual, 
        @cEvaluacionUrlMatriz = :cEvaluacionUrlMatriz, 
        @cEvaluacionObs = :cEvaluacionObs, 
        @dtEvaluacionLiberarMatriz = :dtEvaluacionLiberarMatriz, 
        @dtEvaluacionLiberarCuadernillo = :dtEvaluacionLiberarCuadernillo, 
        @dtEvaluacionLiberarResultados = :dtEvaluacionLiberarResultados', $params);

        return response()->json(['message' => 'Evaluación actualizada exitosamente']);
    }
    // Function para obtener las evaluaciones de participación por evaluación
    public function obtenerParticipaciones(Request $request)
    {
        // Obtener el ID de evaluación del parámetro de consulta
        $iEvaluacionId = $request->query('iEvaluacionId');
        // Verificar si el ID no es nulo antes de hacer la consulta
        if ($iEvaluacionId === null) {
            return response()->json(['error' => 'ID de evaluación no proporcionado'], 400);
        }
        try {
            // Filtrar las participaciones por el ID de evaluación
            $participaciones = DB::table('ere.iiee_participa_evaluaciones')
                ->join('ere.evaluacion', 'ere.iiee_participa_evaluaciones.iEvaluacionId', '=', 'ere.evaluacion.iEvaluacionId')
                ->select('ere.iiee_participa_evaluaciones.iIieeId', 'ere.evaluacion.cEvaluacionNombre')
                ->where('ere.iiee_participa_evaluaciones.iEvaluacionId', $iEvaluacionId) // Agregar la condición where
                ->get();

            return response()->json(['data' => $participaciones]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener las participaciones', 'message' => $e->getMessage()], 500);
        }
    }
}
