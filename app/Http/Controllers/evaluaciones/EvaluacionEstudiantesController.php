<?php

namespace App\Http\Controllers\evaluaciones;

use App\DTO\WhereCondition;
use App\Http\Controllers\ApiController;
use App\Models\eval\BancoPreguntas;
use App\Models\eval\EvaluacionRespuesta;
use App\Models\eval\NivelLogroAlcanzadoEvaluacion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class EvaluacionEstudiantesController extends ApiController
{
    public function index(Request $request)
    {
        $iEvaluacionId = $this->decodeId($request->iEvaluacionId);
        try {
            $data = DB::select('exec eval.SP_SEL_estudiantesEvaluacion @_iEvaluacionId = ? ', [$iEvaluacionId]);
            foreach ($data as &$item) {
                $item->totalPreguntasEvaluacion = (int) $item->totalPreguntasEvaluacion;
                $item->totalPreguntasCalificadas = (int) $item->totalPreguntasCalificadas;
                if ($item->totalPreguntasCalificadas < $item->totalPreguntasEvaluacion) {
                    $item->cEstado = 'PROCESO';
                }
                if ($item->totalPreguntasCalificadas === 0) {
                    $item->cEstado = 'FALTA';
                }
                if ($item->totalPreguntasCalificadas === $item->totalPreguntasEvaluacion) {
                    $item->cEstado = 'REVISADO';
                }
            }
            return $this->successResponse($data, 'Datos obtenidos correctamente');
        } catch (Exception $e) {
            $message = $this->handleAndLogError($e, 'Error al obtener los datos');
            return $this->errorResponse(null, $message);
        } finally {
            unset($item);
        }
    }

    public function obtenerEvaluacionRespuestasEstudiante(Request $request)
    {
        $iEstudianteId = (int) $this->decodeId($request->iEstudianteId);
        $iEvaluacionId = (int) $this->decodeId($request->iEvaluacionId);

        try {
            $data = DB::select(
                'exec eval.SP_SEL_examenEstudianteEvaluacionDocente
                    @_iEstudianteId = ?
                    ,@_iEvaluacionId = ?
                ',
                [$iEstudianteId, $iEvaluacionId]
            );
            $preguntas = (new BancoPreguntas())->procesarPreguntas($data);
            return $this->successResponse($preguntas, 'Datos obtenidos correctamente');
        } catch (Exception $e) {
            $mensaje = $this->handleAndLogError($e, 'Error al obtener los datos');
            return $this->errorResponse(null, $mensaje);
        }
    }

    public function calificarLogros(Request $request)
    {
        $request->validate([
            'iEvalRptaId' => 'required',
            'logrosCalificacion' => 'required'
        ]);

        $esRubrica = $request->esRubrica ?? false;
        $iEvalRptaId = $this->decodeId($request->iEvalRptaId ?? 0);

        DB::beginTransaction();
        try {
            $nivelLogroAlcanzado = new NivelLogroAlcanzadoEvaluacion();
            $resultado = $nivelLogroAlcanzado->calificarLogros(
                $request->logrosCalificacion,
                $iEvalRptaId,
                $esRubrica
            );

            DB::commit();
            return $this->successResponse($resultado, 'Cambios realizados correctamente');
        } catch (Exception $e) {
            DB::rollBack();
            $mensaje = $this->handleAndLogError($e, 'Error en el proceso de calificación');
            return $this->errorResponse(null, $mensaje);
        }
    }

    public function guardarRespuestaxiEstudianteId(Request $request)
    {
        $data = DB::select("
                SELECT 1 FROM eval.evaluaciones
                WHERE iEvaluacionId = ? 
                AND (iEstado = 10 OR (iEstado = 2 AND dtEvaluacionFin < GETDATE()))
            ", [$request->iEvaluacionId]);

        if (!empty($data)) {
            $response = ['validated' => false, 'mensaje' => 'La evaluación ya ha finalizado'];
            $codeResponse = 500;
        } else {
            $evaluacion_respuestas = DB::select(
                "
        SELECT MAX(iEvalRptaId) as iEvalRptaId
        FROM eval.evaluacion_respuestas
        WHERE iEstudianteId = '" . $request->iEstudianteId . "' AND iEvalPregId = '" . $request->iEvalPregId . "'
        "
            );

            if ($evaluacion_respuestas[0]->iEvalRptaId > 0) {
                // Actualizar respuesta existente
                $rpta = DB::update(
                    "
            UPDATE eval.evaluacion_respuestas
            SET jEvalRptaEstudiante = '" . $request->jEvalRptaEstudiante . "'
            WHERE iEvalRptaId = '" . $evaluacion_respuestas[0]->iEvalRptaId . "'
            "
                );

                if ($rpta) {
                    $response = ['validated' => true, 'mensaje' => 'Se actualizó la respuesta.'];
                    $codeResponse = 200;
                } else {
                    $response = ['validated' => false, 'mensaje' => 'No se pudo actualizar la respuesta.'];
                    $codeResponse = 500;
                }
            } else {
                // Insertar nueva respuesta

                $data = DB::select(
                    'exec eval.Sp_INS_evaluacionRespuestasCalificacionxiEstudianteId ?,?,?',
                    [$request->iEstudianteId, $request->iEvalPregId, $request->jEvalRptaEstudiante]
                );

                if ($data[0]->iEvalRptaId) {
                    $response = ['validated' => true, 'mensaje' => 'Se guardó la respuesta.'];
                    $codeResponse = 200; // Código para creación exitosa
                } else {
                    $response = ['validated' => false, 'mensaje' => 'No se pudo guardar la respuesta.'];
                    $codeResponse = 500;
                }
            }
        }
        return new JsonResponse($response, $codeResponse);
    }
}
