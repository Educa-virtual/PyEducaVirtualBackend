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
    // public function actualizarEvaluacion(Request $request, $id)
    // {
    //     // Validar los datos recibidos
    //     $request->validate([
    //         'idTipoEvalId' => 'required|integer', // Cambiado a integer
    //         'iNivelEvalId' => 'required|integer',  // Cambiado a integer
    //         'dtEvaluacionCreacion' => 'required|date',
    //         'cEvaluacionNombre' => 'required|string|max:255',
    //         'cEvaluacionDescripcion' => 'required|string|max:255',
    //         'cEvaluacionUrlDrive' => 'nullable|string|max:255',
    //         'cEvaluacionUrlPlantilla' => 'nullable|string|max:255',
    //         'cEvaluacionUrlManual' => 'nullable|string|max:255',
    //         'cEvaluacionUrlMatriz' => 'nullable|string|max:255',
    //         'cEvaluacionObs' => 'nullable|string|max:255',
    //         'dtEvaluacionLiberarMatriz' => 'required|date',
    //         'dtEvaluacionLiberarCuadernillo' => 'required|date',
    //         'dtEvaluacionLiberarResultados' => 'required|date',
    //     ]);

    //     // Preparar los datos para la actualización
    //     $data = $request->only([
    //         'idTipoEvalId',
    //         'iNivelEvalId',
    //         'dtEvaluacionCreacion',
    //         'cEvaluacionNombre',
    //         'cEvaluacionDescripcion',
    //         'cEvaluacionUrlDrive',
    //         'cEvaluacionUrlPlantilla',
    //         'cEvaluacionUrlManual',
    //         'cEvaluacionUrlMatriz',
    //         'cEvaluacionObs',
    //         'dtEvaluacionLiberarMatriz',
    //         'dtEvaluacionLiberarCuadernillo',
    //         'dtEvaluacionLiberarResultados',
    //     ]);

    //     // Realizar la actualización usando DB::update para llamar al procedimiento almacenado
    //     $result = DB::update(
    //         'EXEC ere.sp_UPD_Evaluaciones 
    //         @iEvaluacionId = ?, 
    //         @idTipoEvalId = ?, 
    //         @iNivelEvalId = ?, 
    //         @dtEvaluacionCreacion = ?, 
    //         @cEvaluacionNombre = ?, 
    //         @cEvaluacionDescripcion = ?, 
    //         @cEvaluacionUrlDrive = ?, 
    //         @cEvaluacionUrlPlantilla = ?, 
    //         @cEvaluacionUrlManual = ?, 
    //         @cEvaluacionUrlMatriz = ?, 
    //         @cEvaluacionObs = ?, 
    //         @dtEvaluacionLiberarMatriz = ?, 
    //         @dtEvaluacionLiberarCuadernillo = ?, 
    //         @dtEvaluacionLiberarResultados = ?',
    //         [
    //             $id, // ID de la evaluación a actualizar
    //             $data['idTipoEvalId'],
    //             $data['iNivelEvalId'],
    //             $data['dtEvaluacionCreacion'],
    //             $data['cEvaluacionNombre'],
    //             $data['cEvaluacionDescripcion'],
    //             $data['cEvaluacionUrlDrive'],
    //             $data['cEvaluacionUrlPlantilla'],
    //             $data['cEvaluacionUrlManual'],
    //             $data['cEvaluacionUrlMatriz'],
    //             $data['cEvaluacionObs'],
    //             $data['dtEvaluacionLiberarMatriz'],
    //             $data['dtEvaluacionLiberarCuadernillo'],
    //             $data['dtEvaluacionLiberarResultados']
    //         ]
    //     );

    //     // Verificar el resultado de la actualización
    //     if ($result) {
    //         return response()->json(['message' => 'Evaluación actualizada con éxito'], 200);
    //     } else {
    //         return response()->json(['message' => 'Error al actualizar la evaluación'], 500);
    //     }
    // }
    public function actualizarEvaluacion(Request $request, $id)
    { // Mostrar el ID recibido

        $id = $request->input('iEvaluacionId'); // Obtener el ID del cuerpo de la solicitud

        // Agregar un log para verificar qué ID se está recibiendo
        //Log::info('ID recibido para actualización:', ['id' => $id]);
        var_dump($id);
        exit;
        // Verificar que $id es un entero
        if (!is_numeric($id)) {

            return response()->json(['message' => 'ID no válido'], 400);
        }
        // Validar los datos recibidos
        $request->validate([
            'idTipoEvalId' => 'required|integer', // Cambiado a integer
            'iNivelEvalId' => 'required|integer',  // Cambiado a integer
            'dtEvaluacionCreacion' => 'required|date',
            'cEvaluacionNombre' => 'required|string|max:255',
            'cEvaluacionDescripcion' => 'required|string|max:255',
            'cEvaluacionUrlDrive' => 'nullable|string|max:255',
            'cEvaluacionUrlPlantilla' => 'nullable|string|max:255',
            'cEvaluacionUrlManual' => 'nullable|string|max:255',
            'cEvaluacionUrlMatriz' => 'nullable|string|max:255',
            'cEvaluacionObs' => 'nullable|string|max:255',
            'dtEvaluacionLiberarMatriz' => 'required|date',
            'dtEvaluacionLiberarCuadernillo' => 'required|date',
            'dtEvaluacionLiberarResultados' => 'required|date',
        ]);

        // Preparar los datos para la actualización
        $data = $request->only([
            'idTipoEvalId',
            'iNivelEvalId',
            'dtEvaluacionCreacion',
            'cEvaluacionNombre',
            'cEvaluacionDescripcion',
            'cEvaluacionUrlDrive',
            'cEvaluacionUrlPlantilla',
            'cEvaluacionUrlManual',
            'cEvaluacionUrlMatriz',
            'cEvaluacionObs',
            'dtEvaluacionLiberarMatriz',
            'dtEvaluacionLiberarCuadernillo',
            'dtEvaluacionLiberarResultados',
        ]);

        try {
            $result = DB::update(
                'EXEC ere.sp_UPD_Evaluaciones 
            @iEvaluacionId = ?, 
            @idTipoEvalId = ?, 
            @iNivelEvalId = ?, 
            @dtEvaluacionCreacion = ?, 
            @cEvaluacionNombre = ?, 
            @cEvaluacionDescripcion = ?, 
            @cEvaluacionUrlDrive = ?, 
            @cEvaluacionUrlPlantilla = ?, 
            @cEvaluacionUrlManual = ?, 
            @cEvaluacionUrlMatriz = ?, 
            @cEvaluacionObs = ?, 
            @dtEvaluacionLiberarMatriz = ?, 
            @dtEvaluacionLiberarCuadernillo = ?, 
            @dtEvaluacionLiberarResultados = ?',
                [
                    $id,
                    $data['idTipoEvalId'],
                    $data['iNivelEvalId'],
                    $data['dtEvaluacionCreacion'],
                    $data['cEvaluacionNombre'],
                    $data['cEvaluacionDescripcion'],
                    $data['cEvaluacionUrlDrive'],
                    $data['cEvaluacionUrlPlantilla'],
                    $data['cEvaluacionUrlManual'],
                    $data['cEvaluacionUrlMatriz'],
                    $data['cEvaluacionObs'],
                    $data['dtEvaluacionLiberarMatriz'],
                    $data['dtEvaluacionLiberarCuadernillo'],
                    $data['dtEvaluacionLiberarResultados']
                ]
            );

            if ($result) {
                return response()->json(['message' => 'Evaluación actualizada con éxito'], 200);
            } else {
                return response()->json(['message' => 'Error al actualizar la evaluación'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
