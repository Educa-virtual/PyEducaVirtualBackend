<?php

namespace App\Http\Controllers\api\acad;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarioAcademicosController extends Controller
{
    public function addCalAcademico(Request $request)
    {
        $solicitud = [
        $request->json,
        $request->opcion,
        ];

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
        
    public function selCalAcademico(Request $request)
    {
        $solicitud = [
        $request->json,
        $request->opcion,
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
