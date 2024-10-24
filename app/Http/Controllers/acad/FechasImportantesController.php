<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FechasImportantesController extends Controller
{
    protected $hashids;
    protected $iCursoId;
    public function __construct(){
        $this->hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
    }
    public function list(Request $request){


        $solicitud = [
            $request->opcion,
            $request->iFechaImpId ?? NULL,
            $request->iTipoFerId ?? NULL,
            $request->iCalAcadId ?? NULL,
            $request->cFechaImpNombre ?? NULL,
            $request->dtFechaImpFecha ?? NULL,
            $request->bFechaImpSeraLaborable ?? NULL,
            $request->cFechaImpURLDocumento ?? NULL,
            $request->cFechaImpInfoAdicional ?? NULL
        ];
        
        $query=DB::select("execute asi.Sp_CRUD_control_asistencias 'CONSULTAR_FECHAS',?,?,?,?,?,?,?,?", $solicitud);
        
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
