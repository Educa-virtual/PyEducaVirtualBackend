<?php

namespace App\Http\Controllers\api\acad;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstudiantesController extends Controller
{
    public function list(Request $request){
        $solicitud = [
            $opcion  = $request->opcion,
            $iCurrId = $request->iCurrId ?? NULL
        ];
        $query=DB::select("execute acad.Sp_SEL_estudiante ?,?",$solicitud);
        
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

        $respuesta = new JsonResponse($response, $estado);

        return $respuesta;
    }
}
