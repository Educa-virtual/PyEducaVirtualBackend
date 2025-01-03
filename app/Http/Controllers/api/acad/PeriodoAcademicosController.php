<?php

namespace App\Http\Controllers\api\acad;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodoAcademicosController extends Controller
{
    public function addPerAcademico(Request $request)
    {
        $solicitud = [
        $request->json,
        $request->_opcion,
        ];

        $query = DB::select("EXEC acad.Sp_ACAD_CAL_PERIODO ?,?", 
        $solicitud);

        try {
        $response = [
            'validated' => true,
            'message' => 'se obtuvo la información',
            'data' => $query,
        ];

        $estado = 200;
        } catch (Exception $e) {
        $response = [
            'validated' => true,
            'message' => $e->getMessage(),
            'data' => [],
        ];
        $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }
        
    public function selPerAcademico(Request $request)
    {
        $solicitud = [
        $request->json,
        $request->_opcion,
        ];
        //@json = N'[{	"jmod": "acad", "jtable": "calendario_academicos"}]'
        $query = DB::select("EXEC acad.Sp_ACAD_CRUD_CALENDARIO ?,?", 
        $solicitud);

        try {
        $response = [
            'validated' => true,
            'message' => 'se obtuvo la información',
            'data' => $query,
        ];

        $estado = 200;
        } catch (Exception $e) {
        $response = [
            'validated' => true,
            'message' => $e->getMessage(),
            'data' => [],
        ];
        
        $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }
   
}
