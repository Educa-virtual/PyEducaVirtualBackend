<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EstadisticasController extends Controller
{
    public function obtenerAniosAcademicos()
    {
        $anios = DB::table('acad.year_academicos')
        ->select('iYAcadId', 'iYearId')
        ->get();

        return response()->json([
            'anios' => $anios,
            
    ]);
    }
    public function obtenerGradosPorSede(Request $request)
    {
        $iSedeId=$request->iIieeId;
        
        
        try {
            $data = DB::select('EXEC acad.Sp_SEL_ObtenerGradosPorSede ?', [$iSedeId]);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = 200;
        } catch (Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $estado = 500;
        }
        return new JsonResponse($response, $estado);
    }
    public function generarReporteNotas(Request $request)
    {
        $validatedData = $request->validate([
            'year' => 'required|integer',
            'grado' => 'required|integer',
            'merito' => 'required|integer',
            'SedeID' => 'required|integer',
        ]);
    
        // Llamamos al procedimiento almacenado
        $reporte = DB::select('EXEC acad.Sp_SEL_GenerarReporteNotas ?, ?, ?, ?', [
            $validatedData['year'],
            $validatedData['grado'],
            $validatedData['merito'],
            $validatedData['SedeID']
        ]);
    
        return response()->json([
            'reporte' => $reporte
        ]);
    }    
}
