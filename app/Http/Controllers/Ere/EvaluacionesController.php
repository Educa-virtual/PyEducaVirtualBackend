<?php

namespace App\Http\Controllers\Ere;

use Illuminate\Support\Facades\Log;

use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use App\Models\Ere\ereEvaluacion; // Importa tu modelo aquí
use App\Models\Ere\EreEvaluacion;
use Hashids\Hashids;

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
            $evaluaciones = DB::select('EXEC ere.SP_SEL_evaluaciones');
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
        @dtEvaluacionLiberarResultados = :dtEvaluacionLiberarResultados', $params);

        return response()->json(['message' => 'Evaluación actualizada exitosamente']);
    }
    //!ESTE ES EL VERDADERO
    // public function obtenerParticipaciones(Request $request)
    // {
    //     // Obtener el ID de evaluación del parámetro de consulta
    //     $iEvaluacionId = $request->query('iEvaluacionId');

    //     // Verificar si el ID no es nulo antes de hacer la consulta
    //     if ($iEvaluacionId === null) {
    //         return response()->json(['error' => 'ID de evaluación no proporcionado'], 400);
    //     }

    //     try {
    //         // Filtrar las participaciones por el ID de evaluación y obtener la información adicional de las instituciones
    //         $participaciones = DB::table('acad.institucion_educativas')
    //             ->join('acad.nivel_tipos', 'acad.institucion_educativas.iNivelTipoId', '=', 'acad.nivel_tipos.iNivelTipoId')
    //             ->join('grl.distritos', 'acad.institucion_educativas.iDsttId', '=', 'grl.distritos.iDsttId')
    //             ->join('grl.provincias', 'grl.distritos.iPrvnId', '=', 'grl.provincias.iPrvnId')
    //             ->leftJoin('ere.iiee_participa_evaluaciones', 'acad.institucion_educativas.iIieeId', '=', 'ere.iiee_participa_evaluaciones.iIieeId')
    //             ->select(
    //                 'acad.institucion_educativas.iIieeId',
    //                 'acad.institucion_educativas.cIieeCodigoModular',
    //                 'acad.institucion_educativas.cIieeNombre',
    //                 'acad.nivel_tipos.cNivelTipoNombre',
    //                 'grl.distritos.cDsttNombre',
    //                 'grl.provincias.cPrvnNombre',
    //                 'ere.iiee_participa_evaluaciones.iEvaluacionId'
    //             )
    //             ->where('ere.iiee_participa_evaluaciones.iEvaluacionId', $iEvaluacionId) // Filtrar por ID de evaluación
    //             ->get();

    //         return response()->json(['data' => $participaciones]);
    //     } catch (Exception $e) {
    //         return response()->json(['error' => 'Error al obtener las participaciones', 'message' => $e->getMessage()], 500);
    //     }
    // }
    //!
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
    //!
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
    //!ELIMINAR CURSO
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
        $cursos = DB::select('EXEC ere.SP_SEL_CursosEvaluacion ?', [$iEvaluacionId]);

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
    //!Agregando CopiarEvaluacion
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
    //!AgregarMatrizCompetencia
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
    //!AgregarMatrizCapacidad
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
    //!AgregarMatrizDesempeno
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

    //!Obtener Especialista y Grado cursos

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
    //!
    // public function obtenerEspDremCurso(Request $request)
    // {
    //     $esquema = 'acad';
    //     $tabla = 'especialistas_DRE';

    //     // Campos de la tabla especialistas_DRE que necesitas
    //     $campos = 'iEspecialistaId, dtEspecialistaInicio, dtEspecialistaRslDesignacion, iDocenteId, iCursosNivelGradId';

    //     // Obtener iPersId de la solicitud
    //     $iPersId = $request->input('iPersId'); // Valor dinámico recibido desde el frontend
    //     //$iPersId = 4;
    //     if (!$iPersId) {
    //         return $this->errorResponse(null, 'El parámetro iPersId es obligatorio.');
    //     }

    //     try {
    //         // Obtener el iDocenteId relacionado con el iPersId
    //         $iDocenteId = DB::table('acad.docentes')
    //             ->where('iPersId', $iPersId)
    //             ->value('iDocenteId');

    //         if (!$iDocenteId) {
    //             return $this->errorResponse(null, 'No se encontró un docente relacionado con el iPersId proporcionado.');
    //         }

    //         // Construir la condición WHERE para la consulta de especialistas_DRE
    //         $where = "iDocenteId = $iDocenteId";

    //         // Parámetros del procedimiento almacenado
    //         $params = [
    //             $esquema,
    //             $tabla,
    //             $campos,
    //             $where
    //         ];

    //         // Ejecutar la consulta principal
    //         $especialistas = DB::select('EXEC grl.sp_SEL_DesdeTabla_Where 
    //     @nombreEsquema = ?, 
    //     @nombreTabla = ?, 
    //     @campos = ?, 
    //     @condicionWhere = ?', $params);

    //         if (empty($especialistas)) {
    //             return $this->errorResponse(null, 'No se encontraron datos para el Especialista relacionado con el iDocenteId.');
    //         }

    //         // Transformar los datos para incluir relaciones (cursos y grados)
    //         $especialistas = collect($especialistas)->map(function ($especialista) {
    //             // Obtener información adicional de cursos y grados
    //             $cursoNivelGrado = DB::table('acad.cursos_niveles_grados as cng')
    //                 ->join('acad.cursos as c', 'cng.iCursoId', '=', 'c.iCursoId')
    //                 ->join('acad.grados as g', 'cng.iNivelGradoId', '=', 'g.iGradoId')
    //                 ->select(
    //                     'cng.iCursoId',
    //                     'c.cCursoNombre',
    //                     'c.cCursoDescripcion',
    //                     'g.cGradoNombre',
    //                     'g.cGradoAbreviacion',
    //                     'g.cGradoRomanos'
    //                 )
    //                 ->where('cng.iCursosNivelGradId', $especialista->iCursosNivelGradId)
    //                 ->first();

    //             // Combinar los datos básicos del especialista con los datos relacionados
    //             return array_merge((array) $especialista, (array) $cursoNivelGrado);
    //         });

    //         return $this->successResponse(
    //             $especialistas,
    //             'Datos obtenidos correctamente.'
    //         );
    //     } catch (Exception $e) {
    //         return $this->errorResponse($e->getMessage(), 'Error al obtener los datos.');
    //     }
    // }

    //!

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
    //! ****
    public function generarPdfMatrizbyEvaluacionId(Request $request)
    {
        // // Obtener el parámetro iEvaluacionId de la solicitud
        // $iEvaluacionId = $request->query('iEvaluacionId');

        // // Validar si se recibió el parámetro
        // if (!$iEvaluacionId) {
        //     return response()->json([
        //         'message' => 'El parámetro iEvaluacionId no fue recibido.',
        //         'status' => 'error'
        //     ], 400); // Código HTTP 400: Bad Request
        // }
        // if (!$iEvaluacionId) {
        //     return response()->json([
        //         'message' => 'La evaluación no existe con el ID proporcionado.',
        //         'status' => 'error'
        //     ], 404); // Código HTTP 404: Not Found
        // }

        // // Si todo está correcto, devolver un mensaje de éxito
        // return response()->json([
        //     'message' => "El ID $iEvaluacionId se recibió correctamente.",
        //     'status' => 'success'
        // ]);
        // $query = DB::select('EXEC ere.SP_SEL_preguntasXiEvaluacionId ?', [$iEvaluacionId]);
        // $pdfData = [
        //     'evaluacion' => $query[0]->cEvaluacionNombre,
        //     'descripcion' => $query[0]->cEvaluacionDescripcion,
        //     'preguntas' => $query,  // El array de preguntas
        // ];

        // // Generar el PDF con la vista correspondiente
        // $pdf = PDF::loadView('pdfEre.matrizReporte', $pdfData);

        // // Retornar el PDF con nombre
        // return $pdf->download('matriz_evaluacioncccccc.pdf');
        //!
        // Obtener el parámetro iEvaluacionId
        $iEvaluacionId = $request->query('iEvaluacionId');

        // Validar si se recibió el parámetro
        if (!$iEvaluacionId) {
            return response()->json([
                'message' => 'El parámetro iEvaluacionId no fue recibido.',
                'status' => 'error'

            ], 400);
        }

        // Datos de ejemplo para el PDF
        $pdfData = [
            'evaluacion' => 'Evaluación de ejemplo: ' . $iEvaluacionId,
            'descripcion' => 'Descripción de la evaluación.',
            'preguntas' => [
                'Pregunta 1: ¿Cuál es el capital de Francia?',
                'Pregunta 2: ¿Quién descubrió América?',
                'Pregunta 3: ¿Cuál es la fórmula del agua?',
            ],
        ];

        // Generar el PDF con los datos proporcionados
        //$pdf = PDF::loadView('pdf.muestra', $pdfData);

        // Descargar el PDF
        //return $pdf->download('matriz_evaluacion.pdf');
    }
}
