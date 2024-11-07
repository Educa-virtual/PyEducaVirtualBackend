<?php

namespace App\Http\Controllers\Evaluaciones;

use App\Http\Controllers\ApiController;
use App\Models\eval\BancoPreguntas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluacionEstudiantesController extends ApiController
{
    public function index(Request $request)
    {
        $iEvaluacionId = $this->decodeId($request->iEvaluacionId);
        try {
            $data = DB::select('exec eval.SP_SEL_estudiantes_evaluacion @_iEvaluacionId = ? ', [$iEvaluacionId]);
            return $this->successResponse($data, 'Datos obtenidos correctamente');
        } catch (Exception $e) {
            $message = $this->handleAndLogError($e, 'Error al obtener los datos');
            return $this->errorResponse(null, $message);
        }
    }

    public function obtenerEvaluacionRespuestasEstudiante(Request $request)
    {
        $iEstudianteId = (int) $this->decodeId($request->iEstudianteId);
        $iEvaluacionId = (int) $this->decodeId($request->iEvaluacionId);

        try {
            $data = DB::select(
                'exec eval.SP_SEL_examen_estudiante_evaluacion_docente
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
}
