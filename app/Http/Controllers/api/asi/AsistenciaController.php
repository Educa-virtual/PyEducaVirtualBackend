<?php

namespace App\Http\Controllers\api\asi;

use App\Http\Controllers\Controller;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsistenciaController extends Controller
{
    protected $hashids;
    protected $iCursoId;
    public function __construct(){
        $this->hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
    }
    public function list(Request $request){
        if ($request->iCursoId) {
            $iCursoId = $this->hashids->decode($request->iCursoId);
            $iCursoId = count($iCursoId) > 0 ? $iCursoId[0] : $iCursoId;
        }
        $solicitud = [
            $request->opcion,
            $iCursoId ?? NULL,
            $request->iCtrlAsistenciaId ?? NULL,
            $request->iHorarioId ?? NULL,
            $request->iDetMatrId ?? NULL,
            $request->iEstudianteId ?? NULL,
            $request->iTipoAsiId ?? NULL,
            $request->dtCtrlAsistencia ?? NULL,
            $request->cCtrlAsistenciaObs ?? NULL,
            $request->iEstado ?? NULL
        ];
        
        $query=DB::select("execute asi.Sp_CRUD_control_asistencias ?,?,?,?,?,?,?,?,?,?", $solicitud);
        
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
