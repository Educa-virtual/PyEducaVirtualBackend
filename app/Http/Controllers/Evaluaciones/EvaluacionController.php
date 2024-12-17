<?php

namespace App\Http\Controllers\Evaluaciones;

use App\DTO\WhereCondition;
use App\Http\Controllers\ApiController;
use App\Models\aula\Evaluacion;
use App\Repositories\aula\ProgramacionActividadesRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class EvaluacionController extends ApiController
{

    public function guardarActualizarEvaluacion(Request $request)
    {

        // guardar actualizar programacion actividad
        $iProgActId = (int) $request->iProgActId ?? 0;
        $iDocenteId = $this->decodeId($request->iDocenteId);
        $iContenidoSemId = $this->decodeId($request->iContenidoSemId);

        $paramsProgramacionActividades = [
            'iProgActId' => $iProgActId,
            'iActTipoId' => $request->iActTipoId,
            'iHorarioId' => $request->iHorarioId ?? null,
            'dtProgActPublicacion' => $request->dtEvaluacionPublicacion,
            'dtProgActInicio' => $request->dtEvaluacionInicio,
            'dtProgActFin' => $request->dtEvaluacionFin ?? null,
            'cProgActTituloLeccion' => $request->cEvaluacionTitulo,
            'cProgActDescripcion' => $request->cEvaluacionDescripcion,
            'iEstado' => 1
        ];

        if ($iProgActId === 0) {
            $paramsProgramacionActividades['iContenidoSemId'] = $iContenidoSemId;
        }

        DB::beginTransaction();
        try {
            $resp = ProgramacionActividadesRepository::guardarActualizar(json_encode($paramsProgramacionActividades));
            if ($iProgActId === 0) {
                $iProgActId = $resp->id;
            }
        } catch (Throwable $e) {
            DB::rollBack();
            $message = $this->handleAndLogError($e, 'Error al guardar la programación');
            return $this->errorResponse(null, $message);
        }

        // evaluacion
        $iEvaluacionId = $request->iEvaluacionId ?? 0;
        $params = [
            $iEvaluacionId,
            $request->iTipoEvalId,
            $iProgActId,
            $request->iInstrumentoId === 0 ? NULL : $request->iInstrumentoId,
            $request->iEscalaCalifId ?? NULL,
            $iDocenteId,
            $request->dtEvaluacionPublicacion ?? NULL,
            $request->cEvaluacionTitulo,
            $request->cEvaluacionDescripcion,
            $request->cEvaluacionObjetivo,
            $request->nEvaluacionPuntaje,
            $request->iEvaluacionNroPreguntas,
            $request->dtEvaluacionInicio ?? NULL,
            $request->dtEvaluacionFin ?? NULL,
            $request->iEvaluacionDuracionHoras,
            $request->iEvaluacionDuracionMinutos,
            $request->cEvaluacionArchivoAdjunto
        ];
        try {
            $data = DB::select('exec eval.SP_INS_UPD_evaluacionAula
                @_iEvaluacionId = ?
                , @_iTipoEvalId = ?
                , @_iProgActId = ?
                , @_iInstrumentoId = ?
                , @_iEscalaCalifId = ?
                , @_iDocenteId = ?
                , @_dtEvaluacionPublicacion = ?
                , @_cEvaluacionTitulo = ?
                , @_cEvaluacionDescripcion = ?
                , @_cEvaluacionObjetivo = ?
                , @_nEvaluacionPuntaje = ?
                , @_iEvaluacionNroPreguntas = ?
                , @_dtEvaluacionInicio = ?
                , @_dtEvaluacionFin = ?
                , @_iEvaluacionDuracionHoras  = ?
                , @_iEvaluacionDuracionMinutos  = ?
                , @_cEvaluacionArchivoAdjunto = ?
            ', $params);
            $data = $data[0];
            $iEvaluacionId = $data->id;
        } catch (Throwable $e) {
            DB::rollBack();
            $errorMessage = $this->handleAndLogError($e, 'Error al guardar la evaluación');
            return $this->errorResponse(null, $errorMessage);
        }

        $responseData = [
            'iProgActId' => $iProgActId,
            'iEvaluacionId' => $iEvaluacionId
        ];

        DB::commit();
        return $this->successResponse($responseData, 'Cambios realizados correctamente');
    }

    public function guardarActualizarPreguntasEvaluacion(Request $request)
    {
        $preguntas = $request->preguntas;
        $iEvaluacionId = $request->iEvaluacionId;

        try {
            $evaluacionPregunta = new Evaluacion();
            $preguntas = $evaluacionPregunta->guardarPreguntas(
                $iEvaluacionId,
                $preguntas
            );

            return $this->successResponse($preguntas, 'Preguntas guardadas correctamente');
        } catch (Throwable $e) {
            $message = $this->handleAndLogError($e, 'Error al guardar los datos');
            return $this->errorResponse(null, $message);
        }
    }

    public function actualizarEvaluacion(Request $request){
        try {

            $params = ['eval','evaluaciones',$request->data];

            $params[] = json_encode([
                'COLUMN_NAME' => 'iEvaluacionId',
                'VALUE' => $request->iEvaluacionId,
            ]);
            
            // Construir los placeholders dinámicos
            $placeholders = implode(',', array_fill(0, count($params), '?'));
            
            // $data = DB::select("exec grl.SP_UPD_EnTablaConJSON $placeholders", $params);

            return $this->successResponse($request->all(), 'Datos obtenidos correctamente');
        } catch (Exception $e) {
            $message = $this->handleAndLogError($e, 'Error al obtener los datos'. $e);
            return $this->errorResponse(null, $message);
        }
    }


    public function eliminarPreguntaEvulacion($id)
    {
        $iEvalPregId = $id;

        try {
            $resp = DB::select('exec eval.SP_DEL_evaluacionPreguntas @_iEvalPregId = ?', [$iEvalPregId]);
            $resp = $resp[0];

            return $this->successResponse($resp->mensaje, 'Se eliminó correctamente');
        } catch (Exception $e) {
            $message = $this->handleAndLogError($e, 'Error al eliminar la pregunta');
            return $this->errorResponse(null, $message);
        }
    }

    public function publicarEvaluacion(Request $request)
    {
        $iEvaluacionId = $this->decodeId($request->iEvaluacionId);
        $paramsGenerarPreguntas = [
            $iEvaluacionId,
            $this->decodeId($request->iCursoId),
            $this->decodeId($request->iSeccionId),
            $this->decodeId($request->iYAcadId),
            $this->decodeId($request->iSemAcadId),
            $this->decodeId($request->iNivelGradoId),
            $this->decodeId($request->iCurrId),
        ];

        DB::beginTransaction();

        try {
            $evaluacion = new Evaluacion();
            $params = ['iEstado' => (int) $request->iEstado];
            $where = [new WhereCondition('iEvaluacionId', $iEvaluacionId)];
            $resp = $evaluacion->actualizarEvaluacion($params, $where);
        } catch (Exception $e) {
            DB::rollBack();
            $message = $this->handleAndLogError($e, 'Error al publicar la evaluación');
            return $this->errorResponse(null, $message);
        }

        // agregar preguntas para los estudiantes
        $resp = null;
        try {
            $resp = DB::select('exec eval.SP_INS_generarPreguntasEstudiantes 
                @_iEvaluacionId = ?
                ,@_iCursoId = ?
                ,@_iSeccionId  = ?
                ,@_iYAcadId = ?
                ,@_iSemAcadId = ?
                ,@_iNivelGradoId = ?
                ,@_iCurrId = ?
            ', $paramsGenerarPreguntas);
        } catch (Exception $e) {
            DB::rollBack();
            $message = $this->handleAndLogError($e, 'Error al generar las preguntas a estudiantes');
            return $this->errorResponse(null, $message);
        }
        DB::commit();
        return $this->successResponse(null, $resp[0]->mensaje);
    }

    public function anularPublicacionEvaluacion(Request $request)
    {
        $iEvaluacionId = $this->decodeId($request->iEvaluacionId ?? 0);
        try {
            $resp = DB::select('exec eval.SP_DEL_anularPublicacionEvaluacionById @_iEvaluacionId = ?', [$iEvaluacionId]);
            $resp = $resp[0];
            return $this->successResponse(null, $resp->mensaje);
        } catch (Exception $e) {
            $mensaje = $this->handleAndLogError($e, 'Error al anular la publicación');
            return $this->errorResponse(null, $mensaje);
        }
    }
}
