<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
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
    public function guardarRecord(Request $request){
        $documento_capturado = $request->cIieeNombre;
        $codigo_modular = $request->codModular;
        $sede_id = $request->sede;
        $reporte_creacion = date('Y-m-d\TH:i:s');
        $order_merito_capturado = $request->merito;
        $grado_capturado = $request->grado;
        $grado_id = $request->gradoid;
        $url_generado = '';
        $year_capturado = $request->year;
        $semestre_acad_id = '';
        $year_id = $request->yearid;
        $grado_id = $request->gradoid;
        $merito_id = $request->meritoid;
        $sede_id = $request->sede;

        
        $semestre = DB::select('SELECT iSemAcadId FROM acad.semestre_academicos WHERE iYAcadId = ?', [$year_id]);
        $semestre_acad_id = $semestre[0]->iSemAcadId ?? null;
        
        $resultado = DB::select('EXEC acad.Sp_SEL_GenerarReporteNotas ?, ?, ?, ?', [
            $year_id, $grado_id, $merito_id, $sede_id
        ]);

        // Verifica si no se obtuvo ningún registro
        if(empty($resultado)) {
            return new JsonResponse([
                'validated' => false,
                'message' => 'No existen datos para el año y grado seleccionados',
                'data' => []
            ], 400);
        }

        $respuesta = [
            "documento_enviado"=>$documento_capturado,
            "year_capturado"=>$year_capturado,
            "order_merito_capturado"=>$order_merito_capturado,
            "grado_capturado"=>$grado_capturado,
            "codigo_modular"=>$codigo_modular,
            "resultado_notas" => $resultado
        ];

        
        $pdf = PDF::loadView('administracion.ranking_reporte', $respuesta )
        ->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true])
        ->setPaper('a4', 'landscape');
           
        $filename = 'reporte_' . time() . '.pdf'; 
        $filepath = 'reports/' . $filename;
        Storage::disk('public')->put($filepath, $pdf->output());

        $baseUrl = $request->input('pdfBaseUrl', config('app.url'));
        
        // $url_generado = asset('storage/reports/' . $filename);
        // $url_generado = rtrim($baseUrl, '/') . '/storage/reports/' . $filename;
        $url_generado = $filepath; 

        $solicitud = [
            'cCodigoModular' => $codigo_modular,
            'iSedeId' => $sede_id,
            'dtReporteCreacion' => $reporte_creacion,
            'cTipoOrdenMerito' => $order_merito_capturado,
            'cGrado' => $grado_capturado,
            'iNivelGradoId' => $grado_id,
            'cUrlGenerado' => $url_generado,
            'cAnio' => $year_capturado,
            'iSemAcadId' => $semestre_acad_id,
            'iYAcadId' => $year_id,
            'dtReporteCreacion'   => DB::raw('GETDATE()') 
        ];

        try {
            $data = DB::table('acad.reportes_record')->insert($solicitud);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = 200;
        } catch (Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $estado = 500;
        }
        return new JsonResponse($response, $estado);
    }

    public function obtenerReportes(Request $request)
    {
        try {
            // Obtener los parámetros del request
            $codModular = $request->input('codModular');
            $year = $request->input('year');
            $grado = $request->input('grado');
            $baseUrl = $request->input('pdfBaseUrl');
            $merito = $request->input('merito');
    
            if (!$codModular) {
                return response()->json(['validated' => false, 'message' => 'El parámetro codModular es requerido.'], 400);
            }
            $query = DB::table('acad.reportes_record')
            ->select([
                'iReporteId',
                'cCodigoModular',
                'iSedeId',
                'cTipoOrdenMerito',
                'cGrado',
                'iNivelGradoId',
                'cUrlGenerado',
                'cAnio',
                'iSemAcadId',
                'iYAcadId',
                DB::raw("FORMAT(dtReporteCreacion, 'yyyy-MM-dd HH:mm:ss') as dtReporteCreacion")
            ])
            ->where('cCodigoModular', $codModular);
            if ($year) {
                $query->where('iYAcadId', $year);
            }
            if ($grado) {
                $query->where('iNivelGradoId', $grado);
            }
            if($merito){
                $query->where('cTipoOrdenMerito',$merito);
            }
            $reportes = $query->orderBy('dtReporteCreacion', 'desc')->get();
            if ($reportes->isEmpty()) {
                return new JsonResponse([
                    'validated' => false,
                    'message' => 'No existen Registros para el año y grado seleccionados',
                    'data' => []
                ], 400);
            }

            foreach ($reportes as $reporte) 
            {
                $reporte->cUrlGenerado = rtrim($baseUrl, '/') . '/storage/' . $reporte->cUrlGenerado;
            }
    
            return response()->json(['validated' => true, 'data' => $reportes], 200);
        } 
        catch (Exception $e) 
        {
            return response()->json(['validated' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function eliminarRecord(Request $request)
    {
        try {
            $id = $request->input('id');
            if (!$id) {
                return response()->json([
                    'validated' => false, 
                    'message' => 'El parámetro id es requerido.'
                ], 400);
            }
            
            // Se utiliza 'iReporteId' para eliminar
            $deleted = DB::table('acad.reportes_record')
                        ->where('iReporteId', $id)
                        ->delete();
    
            if ($deleted) {
                return response()->json([
                    'validated' => true, 
                    'message' => 'Registro eliminado correctamente.'
                ], 200);
            } else {
                return response()->json([
                    'validated' => false, 
                    'message' => 'Registro no encontrado.'
                ], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'validated' => false, 
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
}
