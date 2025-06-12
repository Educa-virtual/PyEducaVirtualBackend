<?php

namespace App\Http\Controllers\api\acad;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeriadoImportanteController extends Controller
{
    public function list(Request $request){
        $opcion                 = $request->opcion;
        $iGradoAcadId           = NULL;
        $cGradoAcadNombre       = NULL;
        $cGradoAcadAbreviado    = NULL;
        $query=DB::select("execute acad.Sp_crud_grado_academicos ?,?,?,?",[$opcion,$iGradoAcadId,$cGradoAcadNombre,$cGradoAcadAbreviado]);
        
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
