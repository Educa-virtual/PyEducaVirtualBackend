<?php

namespace App\Http\Controllers\Ere;

use Illuminate\Support\Facades\Log;

use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use App\Models\Ere\ereEvaluacion; // Importa tu modelo aquí
use App\Models\Ere\EreEvaluacion;
use App\Repositories\Acad\AreasRepository;
use App\Repositories\Ere\EvaluacionesRepository;
use App\Repositories\PreguntasRepository;
use App\Services\Ere\Preguntas\ExportarPreguntasPorAreaWordService;
use Hashids\Hashids;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\Support\ValidatedData;

class EvaluacionesController extends ApiController
{
    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));//new Hashids('PROYECTO VIRTUAL - DREMO', 50);
    }

    public function exportarPreguntasPorArea($iEvaluacionId, $iCursosNivelGradId) {

        if (!is_numeric($iEvaluacionId) && !is_numeric($iCursosNivelGradId)) {
            $iEvaluacionId = $this->hashids->decode($iEvaluacionId)[0];
            $iCursosNivelGradId= $this->hashids->decode($iCursosNivelGradId)[0];
        } else {
            return response()->json(['error' => 'Los ID deben estar cifrados'], 400);
        }

        /*if (!is_numeric($iEvaluacionId)) {
            $iEvaluacionId = $this->hashids->decode($iEvaluacionId)[0];
        } else {
            return response()->json(['error' => 'ID de evaluación no está cifrado'], 400);
        }*/

        $params = [
            'iEvaluacionId' => $iEvaluacionId,  // ID de la evaluación
            'iCursosNivelGradId' => $iCursosNivelGradId,  // ID del área (curso o nivel de grado)
            'busqueda' => '',  // No hay búsqueda definida (si la necesitas, se puede ajustar)
            'iTipoPregId' => 0,  // Suponiendo que es un filtro de tipo de pregunta (cero significa sin filtro)
            'bPreguntaEstado' => 1,  // Sin filtro de estado (puedes ajustarlo si necesitas un valor específico)
            'ids' => NULL // ID de las preguntas (si lo necesitas)
        ];

        $evaluacion = EvaluacionesRepository::obtenerEvaluacionPorId($iEvaluacionId);
        $area = AreasRepository::obtenerAreaPorId($iCursosNivelGradId);
        $preguntasDB = PreguntasRepository::obtenerBancoPreguntasByParams($params);

        if (count($preguntasDB) == 0) {
            return response()->json(['error' => 'No se encontraron preguntas para los parámetros especificados'], 400);
        }

        $exportador = new ExportarPreguntasPorAreaWordService($evaluacion, $area, $preguntasDB);
        return $exportador->exportar();

    }

    public function obtenerEvaluaciones()
    {

        $campos = 'iEvaluacionId,idTipoEvalId,iNivelEvalId,dtEvaluacionCreacion,cEvaluacionNombre,cEvaluacionDescripcion,cEvaluacionUrlDrive,cEvaluacionUrlPlantilla,cEvaluacionUrlManual,cEvaluacionUrlMatriz,cEvaluacionObs,dtEvaluacionLiberarMatriz,dtEvaluacionLiberarCuadernillo,dtEvaluacionLiberarResultados,iEstado,iSesionId,cEvaluacionIUrlCuadernillo,cEvaluacionUrlHojaRespuestas';
        $campos = 'iEvaluacionId,idTipoEvalId,iNivelEvalId,dtEvaluacionCreacion,cEvaluacionNombre,cEvaluacionDescripcion,cEvaluacionUrlDrive,cEvaluacionUrlPlantilla,cEvaluacionUrlManual,cEvaluacionUrlMatriz,cEvaluacionObs,dtEvaluacionLiberarMatriz,dtEvaluacionLiberarCuadernillo,dtEvaluacionLiberarResultados,iEstado,iSesionId';
        $where = '';
        $params = [
            'ere',
            'vistaInstitucionEducativa',
            $campos,
            $where
        ];
        try {
            $evaluaciones = DB::select('EXEC ere.SP_SEL_evaluaciones');
            return $this->successResponse(
                $evaluaciones,
                'Datos obtenidos correctamente'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al obtener los datos');
        }
    }

    public function obtenerEvaluacion(Request $request)
    {
        //die($this->hashids->encode($request->iEvaluacionId));
        if (is_numeric($request->iEvaluacionId)) {
            $iEvaluacionId = $request->iEvaluacionId;
        } else {
            $iEvaluacionId = $this->hashids->decode($request->iEvaluacionId)[0];
        }

        try {
            $evaluacion = DB::selectOne(
                'SELECT * FROM ere.evaluacion AS e
                 INNER JOIN ere.nivel_evaluaciones AS ne ON e.iNivelEvalId=ne.iNivelEvalId
                 WHERE iEvaluacionId = ?',
                [$iEvaluacionId]
            );
            return $this->successResponse(
                $evaluacion,
                'Datos obtenidos correctamente'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Error al obtener los datos');
        }
    }

    public function guardarEvaluacion(Request $request)
    {
        // return $request->all();
        // $iSesionId = $this->hashids->decode($request->iSesionId);
        // if (!empty($iSesionId) && is_array($iSesionId)) {
        //     $iSesionId = $iSesionId[0]; // Asegúrate de acceder al primer valor del array decodificado
        // }
        $params = [
            $request->idTipoEvalId,
            $request->iNivelEvalId,
            $request->cEvaluacionNombre,
            $request->cEvaluacionDescripcion,
            $request->cEvaluacionUrlDrive,
            $request->dtEvaluacionFechaInicio,
            $request->dtEvaluacionFechaFin,
            
        ];
        // return $params;
        // $params = [
        //     $request->idTipoEvalId,
        //     $request->iNivelEvalId,
        //     $request->dtEvaluacionCreacion,
        //     $request->cEvaluacionNombre,
        //     $request->cEvaluacionDescripcion,
        //     $request->cEvaluacionUrlDrive,
        //     $request->cEvaluacionUrlPlantilla,
        //     $request->cEvaluacionUrlManual,
        //     $request->cEvaluacionUrlMatriz,
        //     $request->cEvaluacionObs,
        //     $request->dtEvaluacionLiberarMatriz,
        //     $request->dtEvaluacionLiberarCuadernillo,
        //     $request->dtEvaluacionLiberarResultados,
        //     $request->iEstado,
        //     $iSesionId,
        //     $request->cEvaluacionIUrlCuadernillo,
        //     $request->cEvaluacionUrlHojaRespuestas,
        // ];
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
        $participaciones = $request->input('participaciones'); // Recibimos un array de objetos con iIieeId e iEvaluacionId

        // Iteramos y eliminamos cada participación que coincida con ambos IDs
        foreach ($participaciones as $participacion) {
            DB::table('ere.iiee_participa_evaluaciones')
                ->where('iIieeId', $participacion['iIieeId'])
                ->where('iEvaluacionId', $participacion['iEvaluacionId'])
                ->delete();
        }

        return response()->json(['message' => 'Participaciones eliminadas exitosamente']);
    }
    public function actualizarEvaluacion(Request $request, $iEvaluacionId)
    {
        // $iSesionId = $this->hashids->decode($request->iSesionId);
        // if (!empty($iSesionId) && is_array($iSesionId)) {
        //     $iSesionId = $iSesionId[0]; // Asegúrate de acceder al primer valor del array decodificado
        // }
        // return $request->all();
        // Validar solo los campos opcionales
        $request->validate([
            'idTipoEvalId' => 'nullable|integer',
            'iNivelEvalId' => 'nullable|integer',
            'dtEvaluacionCreacion' => 'nullable|string',
            'cEvaluacionNombre' => 'nullable|string|max:255',
            'cEvaluacionDescripcion' => 'nullable|string|max:255',
            'cEvaluacionUrlDrive' => 'nullable|string|max:255',
            'dtEvaluacionFechaInicio' => 'nullable|string',
            'dtEvaluacionFechaFin' => 'nullable|string',
            
        ]);        
        
        // Preparar los valores para la llamada al procedimiento
        $params = [
            'iEvaluacionId' => $iEvaluacionId,
            'idTipoEvalId' => $request->input('idTipoEvalId', null),
            'iNivelEvalId' => $request->input('iNivelEvalId', null),            
            'cEvaluacionNombre' => $request->input('cEvaluacionNombre', null),
            'cEvaluacionDescripcion' => $request->input('cEvaluacionDescripcion', null),
            'cEvaluacionUrlDrive' => $request->input('cEvaluacionUrlDrive', null),
            'dtEvaluacionFechaInicio' => $request->input('dtEvaluacionFechaInicio', null),
            'dtEvaluacionFechaFin' => $request->input('dtEvaluacionFechaFin', null),
        ];
        // return $params;
        // Construir la llamada dinámica al procedimiento
        //Se cambio el nombre sp_UPD_Evaluaciones
        DB::statement('EXEC ere.SP_UPD_evaluaciones
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
            @dtEvaluacionLiberarResultados = :dtEvaluacionLiberarResultados,
            @iEstado = :iEstado,
            @iSesionId = :iSesionId', $params);
        return response()->json(['message' => 'Evaluación actualizada exitosamente']);
    }

    public function obtenerParticipaciones($iEvaluacionId)
    {
        // Llamar al procedimiento almacenado
        $participaciones = DB::select('EXEC ere.SP_SEL_ObtenerParticipaciones ?', [$iEvaluacionId]);

        // Devolver la respuesta en formato JSON
        return response()->json([
            'data' => $participaciones,
            'message' => 'Participaciones obtenidas correctamente.',
            'status' => true
        ]);
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
                    'iCursoNivelGradId' => $curso['iCursoNivelGradId']
                ]);
            }

            return response()->json(['message' => 'Cursos insertados correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al insertar cursos', 'error' => $e->getMessage()], 500);
        }
    }
    //ELIMINAR CURSO
    public function eliminarCursos(Request $request)
    {
        try {
            $iEvaluacionId = $request->input('iEvaluacionId');
            $selectedCursos = $request->input('selectedCursos');

            // Valida que los datos existan
            if (!$iEvaluacionId || empty($selectedCursos)) {
                return response()->json(['message' => 'Datos incompletos.'], 400);
            }

            // Elimina los cursos
            foreach ($selectedCursos as $curso) {
                DB::table('ere.examen_cursos')
                    ->where('iEvaluacionId', $iEvaluacionId)
                    ->where('iCursoNivelGradId', $curso['iCursoNivelGradId'])
                    ->delete();
            }

            return response()->json(['message' => 'Cursos eliminados correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar cursos', 'error' => $e->getMessage()], 500);
        }
    }
    public function obtenerCursosEvaluacion($iEvaluacionId)
    {
        // Llamar al procedimiento almacenado
        //Se cambio el nombre SP_SEL_CursosEvaluacion
        $cursos = DB::select('EXEC ere.SP_SEL_cursosEvaluacion ?', [$iEvaluacionId]);

        // Devolver la respuesta en formato JSON
        return response()->json([
            'cursos' => $cursos,
            'message' => 'Cursos registrado correctamente.',
            'status' => true
        ]);
    }

    public function obtenerEvaluacionCopia2()
    {

        $campos = 'iEvaluacionId,cEvaluacionNombre';
        $where = '';
        $params = [
            'ere',
            'evaluacion',
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
    // En EvaluacionController.php
    public function actualizarCursosExamen(Request $request)
    {
        // Validación de los datos entrantes
        $request->validate([
            'evaluacion_id' => 'required|integer',
            'cursos' => 'required|array', // Array de cursos
            'cursos.*.id' => 'required|integer', // ID del curso
            'cursos.*.is_selected' => 'required|boolean', // 1 para agregar, 0 para quitar
        ]);

        $evaluacionId = $request->evaluacion_id;
        $cursos = $request->cursos;

        // Iterar sobre los cursos recibidos
        foreach ($cursos as $curso) {
            $cursoId = $curso['id'];
            $isSelected = $curso['is_selected'];

            if ($isSelected == 1) {
                // Agregar el curso a la evaluación si no existe ya
                DB::table('ere.examen_cursos')
                    ->updateOrInsert(
                        ['iEvaluacionId' => $evaluacionId, 'iCursoId' => $cursoId],
                        ['iEvaluacionId' => $evaluacionId, 'iCursoId' => $cursoId]
                    );
            } else {
                // Eliminar el curso de la evaluación si es seleccionado para quitar
                DB::table('ere.examen_cursos')
                    ->where('iEvaluacionId', $evaluacionId)
                    ->where('iCursoId', $cursoId)
                    ->delete();
            }
        }

        return response()->json(['message' => 'Cursos actualizados correctamente']);
    }

    public function actualizarCursos(Request $request)
    {
        // Validar los parámetros recibidos
        $validated = $request->validate([
            'iEvaluacionId' => 'required|integer',
            'cursos' => 'required|array',
            'cursos.*.iCursoId' => 'required|integer',
            'cursos.*.isSelected' => 'required|boolean',
        ]);

        $iEvaluacionId = $validated['iEvaluacionId'];

        // Iterar a través de cada curso y ejecutar el procedimiento almacenado
        foreach ($validated['cursos'] as $curso) {
            DB::statement('EXEC ere.SP_UPD_CursosExamenEvaluacion ?, ?, ?', [
                $iEvaluacionId,
                $curso['iCursoId'],
                $curso['isSelected'],
            ]);
        }

        return response()->json(['message' => 'Cursos actualizados correctamente para la evaluación ' . $iEvaluacionId]);
    }
    //Agregando CopiarEvaluacion
    public function copiarEvaluacion(Request $request)
    {
        // Validar que el parámetro iEvaluacionIdOriginal esté presente
        $request->validate([
            'iEvaluacionIdOriginal' => 'required|integer',
        ]);

        try {
            // Llamar al procedimiento almacenado con el parámetro proporcionado
            $result = DB::statement('EXEC ere.SP_INS_copiarEvaluacionAsociados :iEvaluacionIdOriginal', [
                'iEvaluacionIdOriginal' => $request->input('iEvaluacionIdOriginal'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Copia realizada correctamente.',
            ], 200);
        } catch (\Exception $e) {
            // Capturar errores y devolver una respuesta adecuada
            return response()->json([
                'success' => false,
                'message' => 'Error al realizar la copia.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    //AgregarMatrizCompetencia
    public function obtenerMatrizCompetencias(Request $request)
    {
        $campos = 'iCompetenciaId,cCompetenciaNro,cCompetenciaNombre,cCompetenciaDescripcion,iCurrId'; // Campos específicos que necesitas
        $where = '1=1'; // Condición siempre verdadera para no filtrar los datos

        $params = [
            'acad',
            'curriculo_competencias',
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

            // Generamos la respuesta formateada
            $respuesta = [
                'selectData' => collect($preguntas)->map(function ($pregunta) {
                    return [
                        'iCompetenciaId' => $pregunta->iCompetenciaId,
                        'cCompetenciaNombre' => $pregunta->cCompetenciaNombre
                    ];
                }),
                'fullData' => $preguntas // Incluye todos los datos por si los necesitas después
            ];

            return $this->successResponse(
                $respuesta,
                'Datos obtenidos correctamente'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al obtener los datos');
        }
    }
    //AgregarMatrizCapacidad
    public function obtenerMatrizCapacidades(Request $request)
    {
        $campos = 'iCapacidadId,iCompetenciaId,cCapacidadNombre,cCapacidadDescripcion'; // Campos específicos que necesitas
        $where = '1=1'; // Condición siempre verdadera para no filtrar los datos

        $params = [
            'acad',
            'curriculo_capacidades',
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

            // Generamos la respuesta formateada
            $respuesta = [
                'selectData' => collect($preguntas)->map(function ($pregunta) {
                    return [
                        'iCapacidadId' => $pregunta->iCapacidadId,
                        'cCapacidadNombre' => $pregunta->cCapacidadNombre
                    ];
                }),
                'fullData' => $preguntas // Incluye todos los datos por si los necesitas después
            ];

            return $this->successResponse(
                $respuesta,
                'Datos obtenidos correctamente'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al obtener los datos');
        }
    }
    //AgregarMatrizDesempeno
    public function insertarMatrizDesempeno(Request $request)
    {
        // Validar los datos recibidos en la solicitud
        $validated = $request->validate([
            'iEvaluacionId' => 'required|integer',
            'iCompCursoId' => 'required|integer',
            'iCapacidadId' => 'required|integer',
            'cDesempenoDescripcion' => 'required|string',
            'cDesempenoConocimiento' => 'required|string',
            'iEstado' => 'nullable|integer',
            'iSesionId' => 'nullable|integer',
        ]);


        // Llamar al procedimiento almacenado y capturar el ID retornado
        $result = DB::select('EXEC [ere].[SP_INS_desempenoEvaluacion] ?, ?, ?, ?, ?, ?, ?', [
            $validated['iEvaluacionId'],
            $validated['iCompCursoId'],
            $validated['iCapacidadId'],
            $validated['cDesempenoDescripcion'],
            $validated['cDesempenoConocimiento'],
            $validated['iEstado'] ?? null,
            $validated['iSesionId'] ?? null,
        ]);
        // Capturar el ID retornado
        $iDesempenoId = $result[0]->iDesempenoId;

        // Responder con éxito
        return response()->json([
            'message' =>
            'Datos insertados correctamente',
            'iDesempenoId' => $iDesempenoId,
        ], 201);
    }
    //Obtener Especialista y Grado cursos
    public function obtenerEspDrem(Request $request)
    {
        $campos = 'iEspecialistaId,dtEspecialistaInicio,dtEspecialistaRslDesignacion,iDocenteId,iCursosNivelGradId'; // Campos específicos que necesitas
        $where = '1=1'; // Condición siempre verdadera para no filtrar los datos

        $params = [
            'acad',
            'especialistas_DRE',
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
            return $this->errorResponse($e, 'Error al obtener los datos');
        }
    }

    public function obtenerAreasPorEvaluacionyEspecialista(Request $request) {
        $iPersId = $request->input('iPersId');
        try {
            $iEvaluacionId = $this->hashids->decode($request->input('iEvaluacionId'))[0];
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 'Error al decodificar hash iEvaluacionId');
        }

        if (!$iPersId) {
            return $this->errorResponse(null, 'El parámetro iPersId es obligatorio.');
        }

        if (!$iEvaluacionId) {
            return $this->errorResponse(null, 'El parámetro iEvaluacionId es obligatorio.');
        }

        try {
            $iDocenteId = DB::table('acad.docentes')
                ->where('iPersId', $iPersId)
                ->value('iDocenteId');

            if (!$iDocenteId) {
                return $this->errorResponse(null, 'No se encontró un docente relacionado con el iPersId proporcionado.');
            }

            $resultados = DB::table('acad.especialistas_DRE as ed')
                ->join('ere.examen_cursos as ec', 'ed.iCursosNivelGradId', '=', 'ec.iCursoNivelGradId')
                ->join('acad.cursos_niveles_grados as cng', 'ed.iCursosNivelGradId', '=', 'cng.iCursosNivelGradId')
                ->join('acad.cursos as c', 'cng.iCursoId', '=', 'c.iCursoId')
                ->join('acad.nivel_grados as angr', 'cng.iNivelGradoId', '=', 'angr.iNivelGradoId')
                ->join('acad.grados as g', 'angr.iGradoId', '=', 'g.iGradoId')
                ->join('acad.nivel_ciclos as anici', 'angr.iNivelCicloId', '=', 'anici.iNivelCicloId')
                ->join('acad.nivel_tipos as aniti', 'anici.iNivelTipoId', '=', 'aniti.iNivelTipoId')
                ->join('acad.niveles as ani', 'aniti.iNivelId', '=', 'ani.iNivelId')
                ->select(
                    'c.iCursoId',
                    'ed.iEspecialistaId',
                    'ed.dtEspecialistaInicio',
                    'ed.dtEspecialistaRslDesignacion',
                    'ed.iDocenteId',
                    'ed.iCursosNivelGradId',
                    'c.cCursoNombre',
                    'c.cCursoDescripcion',
                    'c.cCursoImagen',
                    'g.cGradoNombre',
                    'g.cGradoAbreviacion',
                    'g.cGradoRomanos', 'aniti.cNivelTipoNombre'
                )
                ->where('ec.iEvaluacionId', $iEvaluacionId) // Filtrar por la evaluación seleccionada
                ->where('ed.iDocenteId', $iDocenteId)       // Filtrar por el docente relacionado
                ->get();

            if ($resultados->isEmpty()) {
                return $this->errorResponse(null, 'No se encontraron datos para los cursos asociados.');
            }

            foreach ($resultados as $fila) {
                $params = [
                    'iEvaluacionId' => $iEvaluacionId,
                    'iCursosNivelGradId' => $fila->iCursosNivelGradId,
                    'busqueda' => '',
                    'iTipoPregId' => 0,
                    'bPreguntaEstado' => 1,
                    'ids' => NULL
                ];
                $preguntasDB = PreguntasRepository::obtenerBancoPreguntasByParams($params);
                $fila->iCursoId=$this->hashids->encode($fila->iCursosNivelGradId);
                $fila->iCantidadPreguntas=PreguntasRepository::contarPreguntasEre($preguntasDB);
            }

            // Retornar los resultados exitosamente
            return $this->successResponse(
                $resultados,
                'Datos obtenidos correctamente.'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Error al obtener los datos.');
        }
    }

    public function obtenerEspDremCurso(Request $request)
    {
        // Validar los parámetros de entrada
        $iPersId = $request->input('iPersId');
        $iEvaluacionId = $request->input('iEvaluacionId');
        //$iPersId = 1;
        //$iEvaluacionId = 679; //724 no tiene esos dos cursos  - 679 Si tiene esos dos cursos
        if (!$iPersId) {
            return $this->errorResponse(null, 'El parámetro iPersId es obligatorio.');
        }

        if (!$iEvaluacionId) {
            return $this->errorResponse(null, 'El parámetro iEvaluacionId es obligatorio.');
        }

        try {
            // Obtener el iDocenteId relacionado con el iPersId
            $iDocenteId = DB::table('acad.docentes')
                ->where('iPersId', $iPersId)
                ->value('iDocenteId');

            if (!$iDocenteId) {
                return $this->errorResponse(null, 'No se encontró un docente relacionado con el iPersId proporcionado.');
            }

            // Realizar la consulta con filtros y uniones
            $resultados = DB::table('acad.especialistas_DRE as ed')
                ->join('ere.examen_cursos as ec', 'ed.iCursosNivelGradId', '=', 'ec.iCursoNivelGradId')
                ->join('acad.cursos_niveles_grados as cng', 'ed.iCursosNivelGradId', '=', 'cng.iCursosNivelGradId')
                ->join('acad.cursos as c', 'cng.iCursoId', '=', 'c.iCursoId')
                ->join('acad.grados as g', 'cng.iNivelGradoId', '=', 'g.iGradoId')
                ->select(
                    'ed.iEspecialistaId',
                    'ed.dtEspecialistaInicio',
                    'ed.dtEspecialistaRslDesignacion',
                    'ed.iDocenteId',
                    'ed.iCursosNivelGradId',
                    'c.cCursoNombre',
                    'c.cCursoDescripcion',
                    'g.cGradoNombre',
                    'g.cGradoAbreviacion',
                    'g.cGradoRomanos'
                )
                ->where('ec.iEvaluacionId', $iEvaluacionId) // Filtrar por la evaluación seleccionada
                ->where('ed.iDocenteId', $iDocenteId)       // Filtrar por el docente relacionado
                ->get();

            if ($resultados->isEmpty()) {
                return $this->errorResponse(null, 'No se encontraron datos para los cursos asociados.');
            }

            // Retornar los resultados exitosamente
            return $this->successResponse(
                $resultados,
                'Datos obtenidos correctamente.'
            );
        } catch (Exception $e) {
            // Manejo de errores
            return $this->errorResponse($e->getMessage(), 'Error al obtener los datos.');
        }
    }
    public function generarPdfMatrizbyEvaluacionId(Request $request)
    {

        // Acceder a los datos enviados desde Angular
        $iEvaluacionId = $request->input('iEvaluacionId');
        $nombreEvaluacion = $request->input('nombreEvaluacion');
        $areaId = $request->input('areaId'); // Esto se comparará con 'iCursosNivelGradId'
        $ids = $request->input('ids');  // Esto se comparará con 'iPreguntaId'
        $seccion = $request->input('seccion');
        $grado = $request->input('grado');
        $nivel = $request->input('nivel');
        $nombreCurso = $request->input('nombreCurso');
        $especialista = $request->input('especialista');


        // Aquí tomaremos los datos de la tabla "ere.preguntas"
        //        $preguntas = DB::select("EXEC ere.SP_SEL_preguntasXiEvaluacionId ?", [$iEvaluacionId]);
        $preguntas = DB::select("EXEC ere.SP_SEl_evaluacionPreguntas ?", [$iEvaluacionId]);

        // Verificar si se obtuvieron resultados
        if (empty($preguntas)) {
            return response()->json(['error' => 'No se encontraron preguntas para la evaluación especificada'], 404);
        }

        // Filtrar las preguntas según los parámetros recibidos
        $datos = [];
        $dtCreado = null; // Variable para almacenar el dtCreado

        foreach ($preguntas as $key => $pregunta) {
            // Filtrar por areaId (compara con iCursosNivelGradId)
            if ($areaId && $pregunta->iCursosNivelGradId != $areaId) {

                continue; // Si no coincide, omitir esta pregunta
            }

            // Filtrar por ids (compara con iPreguntaId)
            if ($ids && !in_array($pregunta->iPreguntaId, explode(',', $ids))) {

                continue; // Si no coincide, omitir esta pregunta
            }

            // Si pasa los filtros, agregar la pregunta a los datos
            $datos['preguntas'][$key] = [
                'evaluacion_nombre' => $pregunta->cEvaluacionNombre,
                'evaluacion_descripcion' => $pregunta->cEvaluacionDescripcion,
                'competencia_nombre' => $pregunta->cCompetenciaNombre,
                'competencia_descripcion' => $pregunta->cCompetenciaDescripcion,
                'capacidad_nombre' => $pregunta->cCapacidadNombre,
                'capacidad_descripcion' => $pregunta->cCapacidadDescripcion,
                'desempeno_descripcion' => $pregunta->cDesempenoDescripcion,
                //'curso_nombre' => $pregunta->cCursoNombre,
                //'nivel_tipo_nombre' => $pregunta->cNivelTipoNombre,
                //'nivel_nombre' => $pregunta->cNivelNombre,
                'pregunta' => $pregunta->cPregunta,
                'pregunta_clave' => $pregunta->cPreguntaClave,
                'pregunta_texto_ayuda' => $pregunta->cPreguntaTextoAyuda,
                'pregunta_nivel' => $pregunta->iPreguntaNivel,
                'iPreguntaId' => $pregunta->iPreguntaId,
                'iCursosNivelGradId' => $pregunta->iCursosNivelGradId,
                'iEvaluacionId' => $pregunta->iEvaluacionId,
                'dtCreado' => $dtCreado, // Fecha de creación como dato independiente
            ];

            // Capturar el valor de dtCreado de la primera pregunta
            if ($dtCreado === null) {
                $dtCreado = $pregunta->dtCreado;
            }
        }

        // Formatear dtCreado para que solo muestre la fecha (sin la hora)
        if ($dtCreado !== null) {
            $dtCreado = \Carbon\Carbon::parse($dtCreado)->format('Y-m-d'); // Formato "Año-Mes-Día"
        }
        // Verificar si se encontraron preguntas después del filtro
        if (empty($datos['preguntas'])) {
            return response()->json(['error' => 'No se encontraron preguntas que coincidan con los filtros especificados'], 404);
        }
        //CARGAR LOGOS
        $imagePath = public_path('images\logo_IE\Logo-buho.jpg');
        $imageData = base64_encode(file_get_contents($imagePath));
        $virtual = 'data:image/jpeg;base64,' . $imageData;

        $imagePath = public_path('images\logo_IE\dremo.jpg');
        $imageData = base64_encode(file_get_contents($imagePath));
        $region = 'data:image/jpeg;base64,' . $imageData;


        // Preparar los datos para el PDF o la respuesta
        $respuesta = [
            'iEvaluacionId' => $iEvaluacionId,
            'nombreEvaluacion' => $nombreEvaluacion,
            'areaId' => $areaId,
            'ids' => $ids,
            'seccion' => $seccion,
            'grado' => $grado,
            'nivel' => $nivel,
            'nombreCurso' => $nombreCurso,
            'preguntas' => $datos['preguntas'],
            "logoVirtual" => $virtual, // Ruta absoluta
            "imageLogo" => $region, // Ruta absoluta
            'dtCreado' => $dtCreado,
            'especialista' => $especialista
        ];
        // Generar el PDF con los datos recibidos
        $pdf = PDF::loadView('pdfEre.matrizReporte', $respuesta)
            ->setPaper('a4', 'landscape')  // Asegúrate de tener el tamaño de papel correcto
            ->stream('matriz_evaluacion.pdf');  // Puedes cambiar 'stream' por 'download' si quieres forzar la descarga

        // Retornar el PDF como respuesta
        return $pdf;
    }

    public function insertarPreguntaSeleccionada(Request $request)
    {
        // Validar el payload recibido
        $validated = $request->validate([
            'iEvaluacionId' => 'required|integer',
            'preguntas' => 'required|array',
            'preguntas.*.iPreguntaId' => 'required|integer',
        ]);

        // Recorrer las preguntas seleccionadas y formatear los datos para la inserción
        $dataToInsert = array_map(function ($pregunta) use ($validated) {
            return [
                'iPreguntaId' => $pregunta['iPreguntaId'],
                'iEvaluacionId' => $validated['iEvaluacionId'],
            ];
        }, $validated['preguntas']);

        // Verificar si alguna de las preguntas ya existe en la base de datos
        $existingQuestions = DB::table('ere.evaluacion_preguntas')
            ->whereIn('iPreguntaId', array_column($dataToInsert, 'iPreguntaId'))
            ->where('iEvaluacionId', $validated['iEvaluacionId'])
            ->pluck('iPreguntaId') // Obtener solo los iPreguntaId de las preguntas existentes
            ->toArray();

        // Filtrar las preguntas a insertar, excluyendo las que ya existen
        $dataToInsert = array_filter($dataToInsert, function ($pregunta) use ($existingQuestions) {
            return !in_array($pregunta['iPreguntaId'], $existingQuestions);
        });

        // Si hay preguntas para insertar, hacer la inserción
        if (count($dataToInsert) > 0) {
            DB::table('ere.evaluacion_preguntas')->insert($dataToInsert);
        }

        // Retornar una respuesta de éxito
        return response()->json([
            'message' => 'Preguntas seleccionadas guardadas exitosamente.',
            'inserted' => count($dataToInsert),
            'existing' => count($validated['preguntas']) - count($dataToInsert), // Mostrar cuántas ya existían
        ]);
    }

    public function obtenerPreguntaSeleccionada(Request $request)
    {
        $validatedData = $request->validate([
            'iEvaluacionId' => 'required|integer',
        ]);

        $preguntas = DB::table('ere.evaluacion_preguntas')
            ->join('ere.preguntas', 'ere.evaluacion_preguntas.iPreguntaId', '=', 'ere.preguntas.iPreguntaId')
            ->where('ere.evaluacion_preguntas.iEvaluacionId', $validatedData['iEvaluacionId'])
            ->select('ere.preguntas.*', 'ere.evaluacion_preguntas.iEvalPregId')
            ->get();

        return response()->json($preguntas, 200);
    }
    public function obtenerConteoPorCurso(Request $request)
    {
        // Validar los datos de entrada
        $validatedData = $request->validate([
            'iEvaluacionId' => 'required',
            'iCursosNivelGradId' => 'required', // Asegurarse de que ambos parámetros estén presentes
        ]);

        try {
            // Consulta para obtener las preguntas seleccionadas por iEvaluacionId e iCursosNivelGradId
            $resultado = DB::table('ere.evaluacion_preguntas')
                ->join('ere.preguntas', 'ere.evaluacion_preguntas.iPreguntaId', '=', 'ere.preguntas.iPreguntaId')
                ->where('ere.evaluacion_preguntas.iEvaluacionId', $validatedData['iEvaluacionId'])
                ->whereIn('ere.preguntas.iCursosNivelGradId', $validatedData['iCursosNivelGradId'])
                ->select(
                    'ere.preguntas.iPreguntaId', // Otras columnas que necesites
                    'ere.preguntas.cPregunta',  // Por ejemplo, el texto de la pregunta
                    'ere.preguntas.iCursosNivelGradId', // Para asegurarse de que la comparación sea válida
                )
                ->get();

            // Verificar si se encontraron resultados
            if ($resultado->isEmpty()) {
                return response()->json(['message' => 'No se encontraron preguntas para la evaluación y nivel de curso especificados.'], 404);
            }

            // Si hay resultados, devolver los datos
            return response()->json($resultado);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json(['error' => 'Hubo un problema al obtener las preguntas: ' . $e->getMessage()], 500);
        }
    }
    /**
     * Obtener preguntas por EvaluacionId y iPreguntaId
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function obtenerPreguntaInformacion(Request $request)
    // {
    //     // Obtener los parámetros del request
    //     $iEvaluacionId = $request->input('iEvaluacionId');
    //     //$iPreguntaIds = $request->input('iPreguntaIds'); // Recibe la cadena de IDs separados por comas

    //     // Llamar al procedimiento almacenado con los parámetros
    //     $result = DB::select('EXEC ere.SP_SEL_preguntasXiEvaluacionId ?', [$iEvaluacionId]);

    //     // Retornar el resultado como JSON
    //     return response()->json($result);
    // }
    public function obtenerPreguntaInformacion(Request $request)
    {
        // Obtener los parámetros del request
        $iEvaluacionId = $request->input('iEvaluacionId');

        // Llamar al procedimiento almacenado con los parámetros
        $result = DB::select(
            'EXEC ere.SP_SEL_preguntasXiEvaluacionId @iEvaluacionId = ?',
            [$iEvaluacionId]
        );

        // Retornar el resultado como JSON
        return response()->json($result);
    }
    //guardar fecha inicio fin de cursos
    public function guardarInicioFinalExmAreas(Request $request)
    {
        // Validar los datos enviados desde el frontend
        $request->validate([
            'iEvaluacionId' => 'required|integer', // ID de evaluación obligatorio
            'fechaIniFin' => 'required|array', // Debe ser un array
            'fechaIniFin.*.iCursoNivelGradId' => 'required|integer', // Cada objeto debe tener iCursoNivelGradId
            'fechaIniFin.*.fechaInicio' => 'required|date', // Validar que sea una fecha válida
            'fechaIniFin.*.fechaFin' => 'required|date', // Validar que sea una fecha válida
        ]);

        // Obtener los datos validados
        $iEvaluacionId = $request->input('iEvaluacionId');
        $cursos = $request->input('fechaIniFin'); // Array de objetos con fechas y cursos

        try {
            // Iterar sobre cada curso y ejecutar el procedimiento almacenado
            foreach ($cursos as $curso) {
                DB::statement('EXEC ere.SP_UPD_actualizarFechasExamenCursos ?, ?, ?, ?', [
                    $iEvaluacionId,
                    $curso['iCursoNivelGradId'],
                    $curso['fechaInicio'],
                    $curso['fechaFin'],
                ]);
            }

            // Devolver una respuesta de éxito
            return response()->json([
                'success' => true,
                'message' => 'Fechas de examen actualizadas correctamente.',
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores en caso de que falle el procedimiento almacenado
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar las fechas de examen.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar una pregunta de una evaluación.
     */
    public function eliminarPregunta(Request $request)
    {
        // Validar los parámetros recibidos
        $validatedData = $request->validate([
            'iEvaluacionId' => 'required|string',
            'iPreguntaId' => 'required|string',
        ]);
        // Ejecutar el procedimiento almacenado
        $result = DB::statement('EXEC [ere].[SP_DEL_PreguntaDeEvaluacion] :iEvaluacionId, :iPreguntaId', [
            'iEvaluacionId' => $validatedData['iEvaluacionId'],
            'iPreguntaId' => $validatedData['iPreguntaId'],
        ]);
        return response()->json($result);
    }
}
