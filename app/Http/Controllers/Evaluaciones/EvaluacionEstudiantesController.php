<?php

namespace App\Http\Controllers\Evaluaciones;

use App\DTO\WhereCondition;
use App\Http\Controllers\ApiController;
use App\Models\eval\BancoPreguntas;
use App\Models\eval\EvaluacionRespuesta;
use App\Models\eval\NivelLogroAlcanzadoEvaluacion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
