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

        if ($request->iSedeId) {
            $iSedeId = $this->hashids->decode($request->iSedeId);
            $iSedeId = count($iSedeId) > 0 ? $iSedeId[0] : $iSedeId;
        }
        if ($request->iIieeId) {
            $iIieeId = $this->hashids->decode($request->iIieeId);
            $iIieeId = count($iIieeId) > 0 ? $iIieeId[0] : $iIieeId;
        }
        if ($request->iCursoId) {
            $iCursoId = $this->hashids->decode($request->iCursoId);
            $iCursoId = count($iCursoId) > 0 ? $iCursoId[0] : $iCursoId;
        }
        if ($request->iYAcadId) {
            $iYAcadId = $this->hashids->decode($request->iYAcadId);
            $iYAcadId = count($iYAcadId) > 0 ? $iYAcadId[0] : $iYAcadId;
        }
        if ($request->iSeccionId) {
            $iSeccionId = $this->hashids->decode($request->iSeccionId);
            $iSeccionId = count($iSeccionId) > 0 ? $iSeccionId[0] : $iSeccionId;
        }
        if ($request->iNivelGradoId) {
            $iNivelGradoId = $this->hashids->decode($request->iNivelGradoId);
            $iNivelGradoId = count($iNivelGradoId) > 0 ? $iNivelGradoId[0] : $iNivelGradoId;
        }

        $solicitud = [
            $request->opcion,
            $iSedeId ?? NULL,
            $iIieeId ?? NULL,
            $iCursoId ?? NULL,
            $iYAcadId ?? NULL,
            $iSeccionId ?? NULL,
            $iNivelGradoId ?? NULL,
    
        ];
        
        $query=DB::select("execute acad.Sp_CRUD_fechas_importantes ?,?,?,?,?,?,?", $solicitud);
        
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