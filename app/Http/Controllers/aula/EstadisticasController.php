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

        $grados = DB::table('acad.grados')
            ->select('iGradoId', 'cGradoNombre')
            ->get();

        return response()->json([
            'anios' => $anios,
            'grados' => $grados
    ]);
    }
    public function obtenerGradosPorSede($iSedeId)
    {
        $grados = DB::select('EXEC sp_ObtenerGradosPorSede ?', [$iSedeId]);
    
        return response()->json([
            'grados' => $grados
        ]);
    }
}
