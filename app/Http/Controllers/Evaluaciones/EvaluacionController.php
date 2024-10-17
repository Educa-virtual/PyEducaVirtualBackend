<?php

namespace App\Http\Controllers\Evaluaciones;

use App\Http\Controllers\ApiController;
use App\Repositories\aula\ProgramacionActividadesRepository;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class EvaluacionController extends ApiController
{

    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function guardarActualizarEvaluacion(Request $request)
    {

        // guardar actualizar programacion actividad
        $iProgActId = (int) $request->iProgActId ?? 0;
        $iContenidoSemId = $request->iContenidoSemId;
        if ($request->iContenidoSemId) {
            $iContenidoSemId = $this->hashids->decode($iContenidoSemId);
            $iContenidoSemId = count($iContenidoSemId) > 0 ? $iContenidoSemId[0] : $iContenidoSemId;
        }

        $paramsProgramacionActividades = [
            'iProgActId' => $iProgActId,
            'iActTipoId' => $request->iActTipoId,
            'iHorarioId' => $request->iHorarioId ?? null,
            'dtProgActPublicacion' => $request->dtEvaluacionPublicacion,
            'dtProgActInicio' => $request->dtEvaluacionInicio,
            'dtProgActFin' => $request->dtEvaluacionFin ?? null,
            'cProgActTituloLeccion' => $request->cEvaluacionTitulo,
            'cProgActDescripcion' => $request->cEvaluacionDescripcion
        ];

        if ($iProgActId == 0) {
            array_push(
                $paramsProgramacionActividades,
                ['iContenidoSemId' => $iContenidoSemId]
            );
        }

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

        // evaluacion
        $iEvaluacionId = $request->iEvaluacionId ?? 0;
        $params = [
            $iEvaluacionId,
            $request->iTipoEvalId,
            $iProgActId,
            $request->iInstrumentoId ?? NULL,
            $request->iEscalaCalifId ?? NULL,
            $request->iDocenteId,
            $request->dtEvaluacionPublicacion ?? NULL,
            $request->cEvaluacionTitulo,
            $request->cEvaluacionDescripcion,
            $request->cEvaluacionObjetivo,
            $request->nEvaluacionPuntaje,
            $request->iEvaluacionNroPreguntas,
            $request->dtEvaluacionInicio ?? NULL,
            $request->dtEvaluacionFin ?? NULL,
            $request->iEvaluacionDuracionHoras,
            $request->iEvaluacionDuracionMinutos
        ];
        try {
            $data = DB::select('exec eval.Sp_INS_UPD_evaluacion_aula 
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

        DB::beginTransaction();
        foreach ($preguntas  as $key => $pregunta) {
            $camposJson = json_encode(['iEvaluacionId' => $iEvaluacionId, 'iBancoId' => $pregunta['iPreguntaId']]);

            try {
                $params = [
                    'eval',
                    'evaluacion_preguntas',
                    $camposJson
                ];
                $existePregunta = DB::select('select 1 from eval.evaluacion_preguntas where iEvaluacionId = ? AND iBancoId = ?', [$iEvaluacionId, $pregunta['iPreguntaId']]);
                if (count($existePregunta) === 0) {
                    DB::select('exec grl.SP_INS_EnTablaDesdeJSON @Esquema = ?, @Tabla = ?, @DatosJSON = ?', $params);
                }
            } catch (Throwable $e) {
                DB::rollBack();
                $message = $this->handleAndLogError($e, 'Error al guardar los datos');
                return $this->errorResponse(null, $message);
            }
        }
        DB::commit();
        return $this->successResponse(null, 'Alternativas guardadas correctamente');
    }
}
