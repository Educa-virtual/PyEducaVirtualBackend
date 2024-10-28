<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\ApiController;
use App\Repositories\aula\ProgramacionActividadesRepository;
use App\Repositories\Evaluaciones\BancoRepository;
use DateTime;
use DateTimeZone;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

use function PHPUnit\Framework\isNull;
use Illuminate\Http\JsonResponse;

class AulaVirtualController extends ApiController
{

    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function guardarActividad(Request $request)
    {
        // var_dump($request->input('cTareaArchivoAdjunto'));
        // if($request->hasFile('cTareaArchivoAdjunto')){
        //     $archivo = $request->file('cTareaArchivoAdjunto');
        //     $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();

        //     $rutaArchivo = $archivo->storeAs('public/documentos', $nombreArchivo);
        //     $nombreArchivoGuardado = $nombreArchivo;
        // }else{
        //     $nombreArchivoGuardado  = $request->input('cTareaArchivoAdjunto') ?? null;
        // }

        // Extraer fecha y hora desde el request FECHA DE INICIO
        $fechaInicio = $request->input('dFechaEvaluacionPublicacionInicio');
        $horaInicio = $request->input('tHoraEvaluacionPublicacionInicio');
        $date1 = new DateTime($fechaInicio);
        $dateString1 = $date1->format('Y-m-d');

        $horaInicioaux = new DateTime($horaInicio);
        $horaString1 = $horaInicioaux->format('H:i:s');
        $fechaHoraCompletaInicio = $dateString1 . 'T' . $horaString1 . 'Z';
        // FIN

        // Extraer fecha y hora desde el request FECHA FIN
        $fechaFin = $request->input('dFechaEvaluacionPublicacionFin');
        $horaFin = $request->input('tHoraEvaluacionPublicacionFin');
        $date2 = new DateTime($fechaFin);
        $dateString = $date2->format('Y-m-d');

        $horaFinaux = new DateTime($horaFin);
        $horaString = $horaFinaux->format('H:i:s');
        $fechaHoraCompletaFin = $dateString . 'T' . $horaString . 'Z';
        // FIN


        $iProgActId = (int) $request->iProgActId ?? 0;
        $iContenidoSemId = $request->iContenidoSemId;
        if ($request->iContenidoSemId) {
            $iContenidoSemId = $this->hashids->decode($iContenidoSemId);
            $iContenidoSemId = count($iContenidoSemId) > 0 ? $iContenidoSemId[0] : $iContenidoSemId;
        }
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

        // // desde aqui el codigo

        // public function asignarEstudiantes(Request $request)
        // {
        //     $tarea_id = $request->input('tarea_id');
        //     $estudiantes = $request->input('estudiantes');

        //     foreach ($estudiantes as $estudiante_id) {
        //         DB::table('aula_tarea_grupos')->insert([
        //             'tarea_id' => $tarea_id,
        //             'estudiante_id' => $estudiante_id,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     }

        //     return response()->json(['message' => 'Estudiantes asignados correctamente'], 200);
        // }//
        // // fin del codigo

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

        $params = [
            $iProgActId,
            $request->iDocenteId,
            $request->cTareaTitulo,
            $request->cTareaDescripcion,
            $request->cTareaArchivoAdjunto,
            $request->cTareaIndicaciones,
            $request->bTareaEsEvaluado,
            0,
            0,
            $fechaHoraCompletaInicio,
            $fechaHoraCompletaFin,
            null,
            1,
            null,
            $iContenidoSemId,
            $request->iActTipoId


        ];

        try {
            $resp = DB::select('EXEC [aula].[SP_INS_InsertActividades]
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
                    @iActTipoId = ?
            ', $params);
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
    }
    // fin de del codigo

    public function contenidoSemanasProgramacionActividades(Request $request)
    {
        $iSilaboId = $request->iSilaboId;
        $iSilaboId = $this->hashids->decode($iSilaboId);
        $iSilaboId = count($iSilaboId) > 0 ? $iSilaboId[0] : $iSilaboId;

        $params = [$iSilaboId];

        $contenidos = [];
        try {
            $contenidos = DB::select('exec aula.Sp_SEL_contenido_semana_programacion_actividades @_iSilaboId = ?', $params);
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
                ];
            }

            if (!isset($result[$iContenidoSemId]['fechas'][$dtProgActPublicacion]) && !is_null($dtProgActPublicacion)) {
                $contenido = $actividades ? json_decode($actividades, true) : [];
                foreach ($contenido as $key => $contenidoItem) {
                    if (isset($contenido[$key]['ixActivadadId'])) {
                        $contenido[$key]['ixActivadadId'] = $this->hashids->encode($contenidoItem['ixActivadadId']);
                    }
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
        $iProgActId = (int) $request->iProgActId;
        $iActTipoId = (int) $request->iActTipoId;

        DB::beginTransaction();
        // evaluacion
        if ($iActTipoId === 3) {
            $iEvaluacionId = $this->decodeId($request->ixActivadadId);
            try {
                $resp = DB::select('exec eval.Sp_DEL_evaluacion @_iEvaluacionId = ?', [$iEvaluacionId]);
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

    // obtener actviidad
    public function obtenerActividad(Request $request)
    {
        // return $request -> alll();

        $iProgActId = (int) $request->iProgActId;
        $iActTipoId = (int) $request->iActTipoId;

        // evaluaciones
        if ($iActTipoId === 3) {
            $iEvaluacionId = $request->ixActivadadId;
            $iEvaluacionId = $this->hashids->decode($iEvaluacionId);
            $iEvaluacionId = count($iEvaluacionId) > 0 ? $iEvaluacionId[0] : $iEvaluacionId;

            $evaluacion = null;
            try {
                $params = [
                    'iEvaluacionId' => $iEvaluacionId
                ];
                $resp = ProgramacionActividadesRepository::obtenerActividadEvaluacion($params);

                if (count($resp) === 0) {
                    return $this->errorResponse(null, 'La evaluación no existe');
                }
                $evaluacion = $resp[0];
            } catch (Throwable $e) {
                $message = $this->handleAndLogError($e, 'Error al obtener los datos');
                return $this->errorResponse(null, $message);
            }

            try {
                $preguntas = BancoRepository::obtenerPreguntas(['iEvalucionId' => $iEvaluacionId]);

                $evaluacion->preguntas = $preguntas;
            } catch (Throwable $e) {
                $message = $this->handleAndLogError($e, 'Error al obtener los datos');
                return $this->errorResponse(null, $message);
            }

            return $this->successResponse($evaluacion, 'Datos obtenidos correctamente');
        }
    }
    public function obtenerCategorias()
    {
        try {
            $preguntas = DB::select('EXEC aula.Sp_SEL_categoriasXiForoCatId');

            return $this->successResponse(
                $preguntas,
                'Datos Obtenidos Correctamente'
            );
        } catch (Exception $e) {

            return $this->errorResponse($e, 'Error Upssss!');
        }
    }
    public function guardarForo(Request $request)
    {
        //all()
        //return $request -> all();
        // Validar los datos si es necesario
        $request->validate([
            'cForoTitulo' => 'required|string',
            'cForoDescripcion' => 'required|string',
            'iForoCatId' => 'required|integer',
            'dtForoInicio' => 'required|date',
            'iEstado' => 'required|integer',
            'dtForoFin' => 'required|date'
        ]);
        $data = [
            116,
            $request->iForoCatId,
            1,
            $request->cForoTitulo,
            $request->cForoDescripcion,
            $request->dtForoInicio,
            $request->dtForoInicio,
            $request->dtForoFin,
            '',
            $request->iEstado,
            1,

        ];

        $preguntas = DB::select('EXEC [aula].[SP_INS_Foro] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $data);
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

        // return $request -> all();
        $iProgActId = (int) $request->iProgActId;
        $iActTipoId = (int) $request->iActTipoId;

        if ($iActTipoId === 2) {
            $iForoId = $request->ixActivadadId;
            $iForoId = $this->hashids->decode($iForoId);
            $iForoId = count($iForoId) > 0 ? $iForoId[0] : $iForoId;

            $foro = null;
            try {
                $params = [
                    'iForoId' => $iForoId
                ];
                $resp = ProgramacionActividadesRepository::obtenerActividadForo($params);

                if (count($resp) === 0) {
                    return $this->errorResponse(null, 'La evaluación no existe');
                }
                $foro = $resp[0];
            } catch (Throwable $e) {
                $message = $this->handleAndLogError($e, 'Error al obtener los datos');
                return $this->errorResponse(null, $message);
            }

            try {
                $preguntas = BancoRepository::obtenerPreguntas(['iEvalucionId' => $iForoId]);

                $foro->preguntas = $preguntas;
            } catch (Throwable $e) {
                $message = $this->handleAndLogError($e, 'Error al obtener los datos');
                return $this->errorResponse(null, $message);
            }

            return $this->successResponse($foro, 'Datos obtenidos correctamente');
        }
    }
}
