<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $documento_capturado = $request->cIieeNombre;
        $year_capturado = $request->year;
        $order_merito_capturado = $request->merito;
        $grado_capturado = $request->grado;
        $codigo_modular = $request->codModular;
        $year_id = $request->yearid;
        $grado_id = $request->gradoid;
        $merito_id = $request->meritoid;
        $sede_id = $request->sede;

        $resultado = DB::select('EXEC acad.Sp_SEL_GenerarReporteNotas ?, ?, ?, ?', [
            $year_id, $grado_id, $merito_id, $sede_id
        ]);
    
        $respuesta = [
            "documento_enviado"=>$documento_capturado,
            "year_capturado"=>$year_capturado,
            "order_merito_capturado"=>$order_merito_capturado,
            "grado_capturado"=>$grado_capturado,
            "codigo_modular"=>$codigo_modular,
            "resultado_notas" => $resultado
        ];

        $pdf = PDF::loadView('administracion.ranking_reporte', $respuesta)
        ->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true])
        ->setPaper('a4', 'landscape')
        ->stream('reporte.pdf');
        return $pdf;
    }    
}
