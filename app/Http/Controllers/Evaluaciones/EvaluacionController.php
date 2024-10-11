<?php

namespace App\Http\Controllers\Evaluaciones;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class EvaluacionController extends ApiController
{
    public function guardarActualizarEvaluacion(Request $request)
    {
        // evaluacion
        $params = [
            $request->iEvaluacionId,
            $request->iTipoEvalId,
            $request->iProgActId,
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


            // alternativas,

            return $this->successResponse($data, $data->mensaje);
        } catch (Throwable $e) {
            $errorMessage = $this->handleAndLogError($e, 'Error al guardar la evaluación');
            return $this->errorResponse(null, $errorMessage);
        }
    }
}
