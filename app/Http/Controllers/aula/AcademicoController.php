<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class AcademicoController extends Controller
{
    public function obtenerDatos(Request $request){
        
        $datos = $request->documento;
        try {
            $data = DB::select('EXEC aula.SP_SEL_academico ?', $datos);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = 200;
        } catch (Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $estado = 500;
        }
        return new JsonResponse($response, $estado);
    }
    public function reporte(Request $request){
        
        $datos = $request->documento;
        
        $data = DB::select('EXEC aula.SP_SEL_academico ?', $datos);
        $pdf = PDF::loadView('administracion.academico_reporte', $data)
        ->stream('reporte.pdf');
        return $pdf;
    }
}
