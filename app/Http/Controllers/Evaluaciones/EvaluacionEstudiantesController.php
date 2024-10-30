<?php

namespace App\Http\Controllers\Evaluaciones;

use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluacionEstudiantesController extends ApiController
{
    public function index(Request $request)
    {
        $iEvaluacionId = $this->decodeId($request->iEvaluacionId);
        try {
            $data = DB::select('exec eval.Sp_SEL_estudiantes_evaluacion @_iEvaluacionId = ? ', [$iEvaluacionId]);
            return $this->successResponse($data, 'Datos obtenidos correctamente');
        } catch (Exception $e) {
            $message = $this->handleAndLogError($e, 'Error al obtener los datos');
            return $this->errorResponse(null, $message);
        }
    }
}
