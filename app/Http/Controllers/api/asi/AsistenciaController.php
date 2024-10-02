<?php

namespace App\Http\Controllers\api\asi;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsistenciaController extends Controller
{
    public function list(Request $request){
        $solicitud = [
            $request->opcion,
            $request->iCtrlAsistenciaId ?? NULL,
            $request->iHorarioId ?? NULL,
            $request->iDetMatrId ?? NULL,
            $request->iEstudianteId ?? NULL,
            $request->iTipoAsiId ?? NULL,
            $request->dtCtrlAsistencia ?? NULL,
            $request->cCtrlAsistenciaObs ?? NULL,
            $request->iEstado ?? NULL
        ];
        $query=DB::select("execute acad.Sp_CRUD_control_asistencias ?,?,?,?",$solicitud);
        
        try{
            $response = [
                'validated' => true, 
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 200;

        } catch(Exception $e){
            $response = [
                'validated' => true, 
                'message' => $e->getMessage(),
                'data' => [],
            ];
            $estado = 500;
        }

        return new JsonResponse($response,$estado);
    }
}
