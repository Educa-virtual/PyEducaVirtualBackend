<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
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
        $grados = DB::select('EXEC acad.Sp_SEL_ObtenerGradosPorSede ?', [$iSedeId]);
    
        return response()->json([
            'grados' => $grados
        ]);
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
