<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\ApiController;
use App\Repositories\aula\ProgramacionActividadesRepository;
use App\Repositories\evaluaciones\BancoRepository;
use DateTime;
use DateTimeZone;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use function PHPUnit\Framework\isNull;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;

class AulaVirtualController extends ApiController
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function guardarActividad(Request $request)
    {
        // Obtener la fecha y hora de inicio de la evaluación de la solicitud
        $fechaInicio = $request->input('dFechaEvaluacionPublicacionInicio');
        $horaInicio = $request->input('tHoraEvaluacionPublicacionInicio');
        $date1 = new DateTime($fechaInicio);
        $dateString1 = $date1->format('Y-m-d');

        // Formatear la hora de inicio en formato H:i:s
        $horaInicioaux = new DateTime($horaInicio);
        $horaString1 = $horaInicioaux->format('H:i:s');
        // Combinar fecha y hora en un solo string con formato ISO 8601
        $fechaHoraCompletaInicio = $dateString1 . 'T' . $horaString1 . 'Z';

        // Obtener la fecha y hora de fin de la evaluación de la solicitud
        $fechaFin = $request->input('dFechaEvaluacionPublicacionFin');
        $horaFin = $request->input('tHoraEvaluacionPublicacionFin');
        $date2 = new DateTime($fechaFin);
        $dateString = $date2->format('Y-m-d');

        // Formatear la hora de fin en formato H:i:s
        $horaFinaux = new DateTime($horaFin);
        $horaString = $horaFinaux->format('H:i:s');
        // Combinar fecha y hora en un solo string con formato ISO 8601
        $fechaHoraCompletaFin = $dateString . 'T' . $horaString . 'Z';

        // Obtener y decodificar el identificador de programación de actividades
        $iProgActId = (int) $request->iProgActId ?? 0;
        $iContenidoSemId = $request->iContenidoSemId;
        if ($request->iContenidoSemId) {
            $iContenidoSemId = $this->hashids->decode($iContenidoSemId);
            $iContenidoSemId = count($iContenidoSemId) > 0 ? $iContenidoSemId[0] : $iContenidoSemId;
        }

        // Crear un arreglo de parámetros para la programación de actividades
        $paramsProgramacionActividades = [
            'iProgActId' => $iProgActId,
            'iContenidoSemId' => $iContenidoSemId,
            'iActTipoId' => $request->iActTipoId,
            'iHorarioId' => $request->iHorarioId ?? null,
            'dtProgActPublicacion' => $fechaHoraCompletaFin,
            'cProgActTituloLeccion' => $request->cTareaTitulo,
            'cProgActDescripcion' => $request->cTareaDescripcion,
            'cTareaArchivoAdjunto' => $request->cTareaArchivoAdjunto
        ];

        try {
            // Guardar o actualizar la actividad en la base de datos usando el repositorio
            $resp = ProgramacionActividadesRepository::guardarActualizar(json_encode($paramsProgramacionActividades));
            // Si es una nueva actividad, se obtiene su ID
            if ($iProgActId === 0) {
                $iProgActId = $resp->id;
            }
        } catch (Throwable $e) {
            // En caso de error, deshacer la transacción y registrar el error
            DB::rollBack();
            $message = $this->handleAndLogError($e, 'Error al guardar la evaluación');
            return $this->errorResponse(null, $message);
        }

        // Preparar los parámetros para el procedimiento almacenado de insertar actividades
        $params = [
            $iProgActId,
            $request->iDocenteId,
            $request->cTareaTitulo,
            $request->cTareaDescripcion,
            $request->cTareaArchivoAdjunto,
            $request->cTareaIndicaciones,
            $request->bTareaEsEvaluado,
            0,  // Tarea no restringida
            0,  // Tarea no es grupal
            $fechaHoraCompletaInicio,
            $fechaHoraCompletaFin,
            null,  // Sin comentario del docente
            1,     // Estado activo
            null,  // Sin sesión asociada
            $iContenidoSemId,
            $request->iActTipoId
        ];

        try {
            // Ejecutar el procedimiento almacenado para insertar la actividad
            $resp = DB::select(
                'EXEC [aula].[SP_INS_insertActividades]
                    @iProgActId  = ? ,
                    @iDocenteId = ? ,
                    @cTareaTitulo = ?,
                    @cTareaDescripcion = ?,
                    @cTareaArchivoAdjunto = ?,
                    @cTareaIndicaciones = ?,
                    @bTareaEsEvaluado = ?,
                    @bTareaEsRestringida = ?,
                    @bTareaEsGrupal = ?,
                    @dtTareaInicio = ?,
                    @dtTareaFin = ?,
                    @cTareaComentarioDocente = ?,
                    @iEstado = ?,
                    @iSesionId = ?,
                    @iContenidoSemId = ?,
                    @iActTipoId = ?',
                $params
            );
            DB::commit();

            // Verificar si el ID devuelto es mayor a 0 para confirmar el éxito
            if ($resp[0]->id > 0) {
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (Exception $e) {
            // En caso de error en la ejecución del procedimiento almacenado
            DB::rollBack();
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        // Retornar la respuesta JSON con el estado final del proceso
        return new JsonResponse($response, $codeResponse);
    }

    public function contenidoSemanasProgramacionActividades(Request $request)
    {

        $iSilaboId =  VerifyHash::decodes($request->iSilaboId);
        $iContenidoSemId =  VerifyHash::decodes($request->iContenidoSemId);
        $params = [$iSilaboId, $request->perfil, $iContenidoSemId];

        $contenidos = [];
        try {
            $contenidos = DB::select('exec aula.SP_SEL_contenidoSemanaProgramacionActividades @_iSilaboId = ?, @_perfil = ?, @_iContenidoSemId = ?', $params);
        } catch (Throwable $e) {
            $message = $this->handleAndLogError($e, 'Error al obtener los datos');
            return $this->errorResponse(null, $message);
        }

        $result = [];

        foreach ($contenidos as $row) {
            $iContenidoSemId = $row->iContenidoSemId;
            $dtProgActPublicacion = $row->dtProgActPublicacion;
            $actividades = $row->actividadesJSON;

            if (!isset($result[$iContenidoSemId])) {
                $result[$iContenidoSemId] = [
                    'cContenidoSemTitulo' => $row->cContenidoSemTitulo,
                    'cContenidoSemNumero' => $row->cContenidoSemNumero,
                    'iContenidoSemId' =>  $this->hashids->encode($row->iContenidoSemId),
                    'fechas' => [],
                    'iCursoId' => $this->hashids->encode($row->iCursoId),
                    'idDocCursoId' => $this->hashids->encode($row->idDocCursoId),
                    'iNivelGradoId' => $this->hashids->encode($row->iNivelGradoId),
                    'iSemAcadId' => $this->hashids->encode($row->iSemAcadId),
                    'iCurrId' => $this->hashids->encode($row->iCurrId),
                    'iSeccionId' => $this->hashids->encode($row->iSeccionId),
                ];
            }

            if (!isset($result[$iContenidoSemId]['fechas'][$dtProgActPublicacion]) && !is_null($dtProgActPublicacion)) {
                $contenido = $actividades ? json_decode($actividades, true) : [];
                foreach ($contenido as $key => $contenidoItem) {
                    // if (isset($contenido[$key]['ixActivadadId'])) {
                    //     $contenido[$key]['ixActivadadId'] = $this->hashids->encode($contenidoItem['ixActivadadId']);
                    // }
                }
                $result[$iContenidoSemId]['fechas'][$dtProgActPublicacion] =  [
                    'fecha' => $dtProgActPublicacion,
                    'actividades' => $contenido
                ];
            }
        }

        $finalResult =  array_values($result);
        $finalResult = array_map(function ($item) {
            $item['fechas'] = array_values($item['fechas']);
            return $item;
        }, $finalResult);

        return $this->successResponse($finalResult, 'Datos obtenidos correctamente');
    }
    //funcion eliminarActividad
    public function eliminarActividad(Request $request)
    {
        $iProgActId = (int) $this->decodeId($request->iProgActId);
        $iActTipoId = (int) $this->decodeId($request->iActTipoId);
        DB::beginTransaction();
        // evaluacion
        if ($iActTipoId === 3) {
            $iEvaluacionId = $this->decodeId($request->ixActivadadId);
            DB::rollBack();
            try {
                $resp = DB::select('exec eval.SP_DEL_evaluacion @_iEvaluacionId = ?', [$iEvaluacionId]);
            } catch (Throwable $e) {
                DB::rollBack();
                $message = $this->handleAndLogError($e, 'Error al eliminar');
                return $this->errorResponse(null, $message);
            }
        }

        // eliminar programacion actividades
        try {
            $resp = ProgramacionActividadesRepository::eliminar(['iProgActId' => $iProgActId]);
        } catch (Throwable $e) {
            DB::rollBack();
            $message = $this->handleAndLogError($e, 'Error al eliminar');
            return $this->errorResponse(null, $message);
        }
        DB::commit();
        return $this->successResponse(null, 'Eliminado correctamente');
        // eliminar archivos
    }

    public function obtenerActividad(Request $request)
    {
        // Obtiene los parámetros de la solicitud y los convierte en enteros
        $iProgActId = (int) $request->iProgActId;
        $iActTipoId = (int) $request->iActTipoId;

        // Verifica si el tipo de actividad es una evaluación (tipo 3)
        if ($iActTipoId === 3) {
            // Obtiene el ID de la evaluación desde el parámetro de solicitud y lo decodifica usando Hashids
            $fieldsToDecode = [
                'ixActivadadId'
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);
            // Inicializa la variable de evaluación como null
            $iEvaluacionId = $request->ixActivadadId;
            $evaluacion = null;

            // Intenta obtener los datos de la evaluación
            try {

                // Llama al repositorio para obtener la evaluación con los parámetros proporcionados
                $resp = ProgramacionActividadesRepository::obtenerActividadEvaluacion($iEvaluacionId);

                // Si no hay resultados, retorna una respuesta de error
                if (count($resp) === 0) {
                    return $this->errorResponse(null, 'La evaluación no existe');
                }

                // Asigna el primer resultado de la respuesta a la variable evaluación
                $evaluacion = $resp[0];
                $evaluacion->iEstado = (int) $evaluacion->iEstado;
            } catch (Throwable $e) {
                // Maneja cualquier error que ocurra durante la obtención de datos y retorna un mensaje de error
                $message = $this->handleAndLogError($e, 'Error al obtener los datos');
                return $this->errorResponse(null, $message);
            }

            // Intenta obtener las preguntas de la evaluación desde el repositorio
            try {
                $preguntas = BancoRepository::obtenerPreguntas(['iEvalucionId' => $iEvaluacionId]);

                // Asigna las preguntas a la propiedad "preguntas" del objeto evaluación
                $evaluacion->preguntas = $preguntas;
            } catch (Throwable $e) {
                // Maneja errores durante la obtención de las preguntas y retorna un mensaje de error
                $message = $this->handleAndLogError($e, 'Error al obtener los datos');
                return $this->errorResponse(null, $message);
            }

            // Decodifica el archivo adjunto de la evaluación en formato JSON
            $evaluacion->cEvaluacionArchivoAdjunto = json_decode($evaluacion->cEvaluacionArchivoAdjunto ?? '[]');

            // Retorna una respuesta exitosa con los datos de la evaluación
            return $this->successResponse($evaluacion, 'Datos obtenidos correctamente');
        }
    }

    public function obtenerCategorias()
    {
        try {
            $preguntas = DB::select('EXEC aula.Sp_SEL_categoriasXiForoCatId');

            //return $preguntas;
            return $this->successResponse($preguntas);
        } catch (Exception $e) {

            return $this->errorResponse($e, 'Error Upssss!');
        }
    }
    public function obtenerEstudiantesMatricula(Request $request)
    {
        $iCursoId = '1';
        $iSemAcadId = '1';
        $iYAcadId = '1';

        // $params =[
        //     $iCursoId,
        //     $iSemAcadId,
        //     $iYAcadId
        // ];
        try {
            $preguntas = DB::select('EXEC acad.Sp_SEL_consulta_matriculados ?', [$iCursoId], [$iSemAcadId], [$iYAcadId]);

            //return $preguntas;
            return $this->successResponse($preguntas);
        } catch (Exception $e) {

            return $this->errorResponse($e, 'Error Upssss!');
        }
    }
    public function guardarForo(Request $request)
    {

        // Validar los datos si es necesario
        $request->validate([
            'cForoTitulo' => 'required|string',
            'cForoDescripcion' => 'required|string',
            'iForoCatId' => 'required|integer',
            'cForoUrl' => 'required|string',
            //'dtForoInicio' => 'required|date',
            'iEstado' => 'required|integer',
            //'dtForoFin' => 'required|date'
        ]);
        $iProgActId = (int) $request->iProgActId ?? 0;
        $iContenidoSemId = $request->iContenidoSemId;
        if ($request->iContenidoSemId) {
            $iContenidoSemId = $this->hashids->decode($iContenidoSemId);
            $iContenidoSemId = count($iContenidoSemId) > 0 ? $iContenidoSemId[0] : $iContenidoSemId;
        }
        $paramsProgramacionActividades = [
            'iProgActId' => $iProgActId,
            'iContenidoSemId' => $iContenidoSemId,
            'iActTipoId' => 2,
            'iHorarioId' => $request->iHorarioId ?? null,
            'dtProgActPublicacion' => $request->dtForoPublicacion,
            'dtProgActInicio' => $request->dtForoInicio,
            'dtProgActFin' => $request->dtForoFin,
            'cProgActTituloLeccion' => $request->cForoTitulo,
            'cProgActDescripcion' => $request->cForoDescripcion,
            'iEstado' => $request->iEstado

        ];

        DB::beginTransaction();
        try {
            $resp = ProgramacionActividadesRepository::guardarActualizar(json_encode($paramsProgramacionActividades));
            if ($iProgActId === 0) {
                $iProgActId = $resp->id;
            }
        } catch (Throwable $e) {
            DB::rollBack();
            $message = $this->handleAndLogError($e, 'Error al guardar la evaluación');
            return $this->errorResponse(null, $message);
        }

        $data = [
            $iProgActId,
            $request->iForoCatId,
            1,
            $request->cForoTitulo,
            $request->cForoDescripcion,
            $request->dtForoInicio,
            $request->dtForoInicio,
            $request->dtForoFin,
            $request->cForoUrl,
            $request->iEstado,
            1,

        ];
        try {
            $resp = DB::select('EXEC [aula].[SP_INS_Foro] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $data);

            DB::commit();
            if ($resp[0]->id > 0) {

                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);

        // $preguntas = DB::select('EXEC [aula].[SP_INS_Foro] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $data);
    }
    public function eliminarRptEstudiante(Request $request)
    {
        $validated = $request->validate([
            'iForoRptaId' => 'required|string',
        ]);
        $params = [$request['iForoRptaId']];
        //return $params;
        try {
            // Llamar al procedimiento almacenado
            DB::select('exec aula.SP_DEL_respuestaXidEstudiante @iForoRptaId = ?', $params);
            // Responder con éxito
            return response()->json([
                'success' => true,
                'message' => 'Elemento eliminado correctamente',
            ], 200);
        } catch (Throwable $e) {
            // Manejo de errores
            $message = $this->handleAndLogError($e, 'Error al eliminar');
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 500);
        }
    }
    // Guardar respuesta de Foro
    public function guardarRespuesta(Request $request)
    {
        //return $request -> all();
        $request->validate(
            [
                //'iEstudianteId' => 'required|integer',
                'cForoRptaRespuesta' => 'required|string',
                'iForoId' => 'required|string'
            ],
            ['cForoRptaRespuesta.required' => 'El comentario es obligatorio',]
        );
        $fieldsToDecode = [
            'iDocenteId',
            'iEstudianteId',
            'iForoId',
            'iForoRptaId',
            'iCredId',
        ];
        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

        if (isset($request->iForoRptaId)) {
            $request->merge(['iForoRptaPadre' => $request->iForoRptaId]);
        }
        $data = [
            $request->iEstudianteId            ?? NULL,
            $request->iForoId                  ?? NULL,
            $request->iForoRptaPadre           ?? NULL,
            $request->iDocenteId               ?? NULL,
            $request->cForoRptaRespuesta       ?? NULL,
            $request->nForoRptaNota            ?? NULL,
            $request->cForoRptaDocente         ?? NULL,
            $request->iEscalaCalifId           ?? NULL

        ];

        try {

            $resp = DB::select('EXEC [aula].[SP_INS_RespuestaForo]
                @_iEstudianteId = ?,
                @_iForoId = ?,
                @_iForoRptaPadre = ?,
                @_iDocenteId = ?,
                @_cForoRptaRespuesta = ?,
                @_nForoRptaNota = ?,
                @_cForoRptaDocente = ?,
                @_iEscalaCalifId = ?', $data);


            if ($resp[0]->iForoRptaId > 0) {
                $message = 'Se ha guardado correctamente';
                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => $data],
                    Response::HTTP_OK
                );
            } else {
                $message = 'No se ha podido guardar';
                return new JsonResponse(
                    ['validated' => false, 'message' => $message, 'data' => []],
                    Response::HTTP_OK
                );
            }
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $resp];
            $estado = 200;

            return $response;
        } catch (Exception $e) {
            $this->handleAndLogError($e);
            DB::rollBack();
            $response = ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }
    public function obtenerCalificacion()
    {
        try {
            $preguntas = DB::select('EXEC aula.Sp_SEL_escalaCalificacion');

            return $this->successResponse(
                $preguntas,
                'Datos Obtenidos Correctamente'
            );
        } catch (Exception $e) {

            return $this->errorResponse($e, 'Error Upssss!');
        }
    }
    public function obtenerForo(Request $request)
    {

        $fieldsToDecode = [
            'ixActivadadId',
            'iActTipoId'
        ];

        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

        if ($request->iActTipoId === '2') {

            $params = [
                $request->ixActivadadId
            ];
            try {

                $data =  DB::select('exec aula.SP_SEL_Foro ?', $params);

                $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
                $estado = 200;

                return $response;
            } catch (\Exception $e) {
                $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
                $estado = 500;
            }

            return $this->successResponse($response, $estado, 'Datos obtenidos correctamente');
        }
    }
    public function obtenerRespuestaForo(Request $request)
    {
        $fieldsToDecode = [
            'ixActivadadId',
            'iActTipoId'
        ];

        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

        // Verificar si el tipo de actividad es 2 (específico para foros)
        if ($request->iActTipoId === '2') {

            // Inicializar la variable donde se almacenarán los datos del foro
            $foro = null;

            try {
                // Llamar al repositorio para obtener las respuestas del foro según el ID
                $resp = ProgramacionActividadesRepository::obtenerRespuestaActividadForo($request->ixActivadadId);

                // Verificar si se encontraron respuestas en el foro; si no, devolver un mensaje de error
                if (count($resp) === 0) {
                    return $this->errorResponse(null, 'El Foro no existe');
                }

                // Asignar la respuesta del repositorio a la variable 'foro'
                $foro = $resp;
            } catch (Throwable $e) {
                // En caso de excepción, manejar y registrar el error
                $message = $this->handleAndLogError($e, 'Error al obtener los datos');
                // Devolver la respuesta de error con el mensaje generado
                return $this->errorResponse(null, $message);
            }

            // Si todo sale bien, devolver la respuesta exitosa con los datos del foro
            return $this->successResponse($foro, 'Datos obtenidos correctamente');
        }
    }
    public function calificarForoDocente(Request $request)
    {

        $fieldsToDecode = [
            'iEstudianteId',
            'iForoId',
        ];
        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);
        $request->merge(['cForoRptaDocente' => $request->cForoRptDocente]);

        $params = [
            $request->iEstudianteId      ??  NULL,
            $request->iForoId            ??  NULL,
            $request->cForoRptaDocente    ??  NULL
        ];


        try {
            // Llama al procedimiento almacenado 'Sp_UPD_calificarDocenteForoRespuestas' en la base de datos,
            // pasándole los parámetros preparados. Este procedimiento se encarga de actualizar la calificación.
            $data = DB::select('exec aula.Sp_UPD_calificarDocenteForoRespuestasEstudiante ?,?,?', $params);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = 200;

            return $response;
            // // Si la respuesta de la base de datos tiene un ID válido de respuesta de foro, significa que la operación fue exitosa.
            // if ($data[0]->iForoRptaId > 0) {
            //     // Crea un mensaje de éxito y asigna un código de respuesta HTTP 200 (OK).
            //     $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            //     $codeResponse = 200;
            // } else {
            //     // Si el ID no es válido, significa que no se pudo guardar la información, y se asigna un código de error 500.
            //     $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            //     $codeResponse = 500;
            // }
        } catch (\Exception $e) {
            // Si ocurre una excepción, se captura y se envía un mensaje de error con los detalles del mismo.
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500; // Código de error HTTP 500 en caso de falla del servidor
        }

        // Retorna una respuesta JSON con el mensaje y el código HTTP correspondiente.
        return new JsonResponse($response, $codeResponse);
    }
    // Obtener retrolimentación del docente del foro
    public function obtenerReptdocente(Request $request)
    {
        // return $request->all();
        $request->validate([
            'iEstudianteId' => 'required|string',
            'iForoId' => 'required|string'
        ]);

        $iEstudianteId = $request->iEstudianteId;
        $iForoId = $request->iForoId;
        $params = [
            $iEstudianteId,
            $iForoId
        ];

        // return $params;
        //return $params;
        try {
            $data = DB::select('EXEC aula.SP_SEL_obtenerForoRespuestasXidEstudiante ?,?', $params);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = 200;

            return $response;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }
    public function guardarComentarioRespuesta(Request $request)
    {

        $request->validate([
            'cForoRptaPadre' => 'required|string',
            'iForoRptaId' => 'required|string'
        ]);

        $request['iEstudianteId'] = is_null($request->iEstudianteId)
            ? null
            : (is_numeric($request->iEstudianteId)
                ? $request->iEstudianteId
                : ($this->hashids->decode($request->iEstudianteId)[0] ?? null));

        $request['iDocenteId'] = is_null($request->iDocenteId)
            ? null
            : (is_numeric($request->iDocenteId)
                ? $request->iDocenteId
                : ($this->hashids->decode($request->iDocenteId)[0] ?? null));

        $request['iForoRptaId'] = is_null($request->iForoRptaId)
            ? null
            : (is_numeric($request->iForoRptaId)
                ? $request->iForoRptaId
                : ($this->hashids->decode($request->iForoRptaId)[0] ?? null));

        $data = [
            $request->iEstudianteId     ?? NULL,
            $request->iDocenteId        ?? NULL,
            $request->iForoRptaId       ?? NULL,
            $request->cForoRptaPadre    ?? NULL
        ];
        //return $data;
        try {
            $resp = DB::select('EXEC [aula].[SP_INS_RespuestaPadre]
               ?,?,?,?', $data);
            DB::commit();
            if ($resp[0]->id > 0) {
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (Exception $e) {
            $this->handleAndLogError($e);
            DB::rollBack();
            $response = ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }
    public function maestroDetalle(Request $request)
    {
        $solicitud = [

            $request->Esquema,       //-- Esquema de la tabla maestra
            $request->TablaMaestra, //NVARCHAR(128),   -- Nombre de la tabla maestra
            $request->DatosJSONMaestro, // NVARCHAR(MAX), -- Datos en formato JSON para la tabla maestra
            $request->TablaDetalle, // NVARCHAR(128),   -- Nombre de la tabla detalle
            $request->DatosJSONDetalles, // NVARCHAR(MAX), -- Datos en formato JSON (array) para los detalles
            $request->campoFK // NVARCHAR(128)

        ];

        $query = DB::select(
            "EXEC grl.SP_INS_EnTablaMaestroDetalleDesdeJSON ?,?,?,?,?,?", //actualizado
            $solicitud
        );

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 201;
        } catch (Exception $e) {
            $response = [
                'validated' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ];
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }
}
