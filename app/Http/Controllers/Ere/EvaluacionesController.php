<?php

namespace App\Http\Controllers\Ere;


use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use App\Models\Ere\ereEvaluacion; // Importa tu modelo aquí
use App\Models\Ere\EreEvaluacion;
//use Carbon\Carbon;

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

    public function eliminarParticipacion(Request $request)
    {
        $ids = $request->input('ids'); // Recibimos un array de IDs de participaciones
        DB::table('ere.iiee_participa_evaluaciones')
            ->whereIn('iIieeId', $ids)  // Eliminamos todas las participaciones con los IDs proporcionados
            ->delete();

        return response()->json(['message' => 'Participaciones eliminadas exitosamente']);
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

    public function obtenerParticipaciones(Request $request)
    {
        // Obtener el ID de evaluación del parámetro de consulta
        $iEvaluacionId = $request->query('iEvaluacionId');

        // Verificar si el ID no es nulo antes de hacer la consulta
        if ($iEvaluacionId === null) {
            return response()->json(['error' => 'ID de evaluación no proporcionado'], 400);
        }

        try {
            // Filtrar las participaciones por el ID de evaluación y obtener la información adicional de las instituciones
            $participaciones = DB::table('acad.institucion_educativas')
                ->join('acad.nivel_tipos', 'acad.institucion_educativas.iNivelTipoId', '=', 'acad.nivel_tipos.iNivelTipoId')
                ->join('grl.distritos', 'acad.institucion_educativas.iDsttId', '=', 'grl.distritos.iDsttId')
                ->join('grl.provincias', 'grl.distritos.iPrvnId', '=', 'grl.provincias.iPrvnId')
                ->leftJoin('ere.iiee_participa_evaluaciones', 'acad.institucion_educativas.iIieeId', '=', 'ere.iiee_participa_evaluaciones.iIieeId')
                ->select(
                    'acad.institucion_educativas.iIieeId',
                    'acad.institucion_educativas.cIieeCodigoModular',
                    'acad.institucion_educativas.cIieeNombre',
                    'acad.nivel_tipos.cNivelTipoNombre',
                    'grl.distritos.cDsttNombre',
                    'grl.provincias.cPrvnNombre',
                    'ere.iiee_participa_evaluaciones.iEvaluacionId'
                )
                ->where('ere.iiee_participa_evaluaciones.iEvaluacionId', $iEvaluacionId) // Filtrar por ID de evaluación
                ->get();

            return response()->json(['data' => $participaciones]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener las participaciones', 'message' => $e->getMessage()], 500);
        }
    }
    public function obtenerCursos()
    {
        $campos = 'iCursoId,cCursoNombre';
        $where = '';
        $params = [
            'acad',
            'cursos',
            $campos,
            $where
        ];
        try {
            $preguntas = DB::select('EXEC grl.sp_SEL_DesdeTabla_Where
                @nombreEsquema = ?,
                @nombreTabla = ?,    
                @campos = ?,        
                @condicionWhere = ?
            ', $params);

            return $this->successResponse(
                $preguntas,
                'Datos obtenidos correctamente'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Erro No!');
        }
    }
    public function insertarCursos(Request $request)
    {
        try {
            $iEvaluacionId = $request->input('iEvaluacionId');
            $selectedCursos = $request->input('selectedCursos');

            // Valida que los datos existan
            if (!$iEvaluacionId || empty($selectedCursos)) {
                return response()->json(['message' => 'Datos incompletos.'], 400);
            }

            // Inserta los cursos
            foreach ($selectedCursos as $curso) {
                DB::table('ere.examen_cursos')->insert([
                    'iEvaluacionId' => $iEvaluacionId,
                    'iCursoId' => $curso['iCursoId']
                ]);
            }

            return response()->json(['message' => 'Cursos insertados correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al insertar cursos', 'error' => $e->getMessage()], 500);
        }
    }

    public function obtenerCursosEvaluacion($iEvaluacionId)
    {
        // Llamar al procedimiento almacenado
        $cursos = DB::select('EXEC ere.sp_SEL_CursosEvaluacion ?', [$iEvaluacionId]);

        // Devolver la respuesta en formato JSON
        return response()->json([
            'cursos' => $cursos,
            'message' => 'Cursos clasificados correctamente.',
            'status' => true
        ]);
    }

    //Actualizar Cursos Examen Evaluacion COMENTADO
    public function actualizarCursosEvaluacion($iEvaluacionId, $cursosSeleccionados)
    {
        // Convertir el array de cursos seleccionados en una cadena separada por comas
        $cursosSeleccionadosStr = implode(',', $cursosSeleccionados);

        // Llamar al procedimiento almacenado
        DB::select('EXEC ere.SP_UPD_CursosEvaluacion ?, ?', [$iEvaluacionId, $cursosSeleccionadosStr]);

        // Devolver la respuesta
        return response()->json([
            'message' => 'Cursos actualizados correctamente.',
            'status' => true
        ]);
    }
}
