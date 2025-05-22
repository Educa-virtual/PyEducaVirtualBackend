<?php

namespace App\Http\Controllers\Ere;

use App\Http\Controllers\Controller;
use App\Services\ParseSqlErrorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReporteEvaluacionesController extends Controller
{
    public function obtenerEvaluacionesCursosIes(Request $request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iYAcadId,
            $request->iEvaluacionId,
            $request->iCursoNivelGradoId,
            $request->iIieeId,
            $request->iDsttId,
            $request->iUgelId,
            $request->iNivelTipoId,
            $request->iNivelGradoId,
            $request->iSeccionId,
        ];

        try {
            $data = DB::select('EXEC ere.Sp_SEL_evaluacionesInformeOpt ?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'mensaje' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'mensaje' => $error_message];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    public function obtenerInformeResumen(Request $request)
    {
        $parametros = [
            $request->iYAcadId,
            $request->iEvaluacionId,
            $request->iCursoId,
            $request->iNivelTipoId,
            $request->iNivelGradoId,
            $request->iSeccionId,
            $request->iDsttId,
            $request->cPersSexo,
            $request->iUgelId,
            $request->iIieeId,
            $request->iSedeId,
            $request->iTipoSectorId,
            $request->iZonaId,
            $request->iCredEntPerfId,
            0, // No mostrar detalle en vista
            $request->cTipoReporte,
        ];

        try {
            $data = DB::selectResultSets('EXEC ere.SP_SEL_evaluacionInformeResumenOpt ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'mensaje' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'mensaje' => $error_message];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    public function generarPdf(Request $request)
    {
        $parametros = [
            $request->iYAcadId,
            $request->iEvaluacionId,
            $request->iCursoId,
            $request->iNivelTipoId,
            $request->iNivelGradoId,
            $request->iSeccionId,
            $request->iDsttId,
            $request->cPersSexo,
            $request->iUgelId,
            $request->iIieeId,
            $request->iSedeId,
            $request->iTipoSectorId,
            $request->iZonaId,
            $request->iCredEntPerfId,
            1, // Mostrar detalle en PDF
            $request->cTipoReporte,
        ];

        try {
            $data = DB::selectResultSets('EXEC ere.SP_SEL_evaluacionInformeResumenOpt ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'mensaje' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'mensaje' => $error_message];
            $codeResponse = 500;
            return response()->json($response, $codeResponse);
        }

        $filtros = $data[0][0];
        $resultados = $data[1];
        $niveles = $data[2];
        $resumen = $data[3];
        $matriz = $data[4];
        $ies = [];

        foreach ( $resultados as $resultado) {
            $resultado->respuestas = json_decode($resultado->respuestas);
        }
        if( $filtros->tipo_reporte == 'IE' && key_exists(5, $data) ) {
            $ies = $data[5];
            foreach ( $ies as $ie) {
                $sumatoria = 0;
                foreach( $niveles as $nivel) {
                    $nivel_logro_id = strval($nivel?->nivel_logro_id ?? '');
                    $sumatoria += intval($ie?->$nivel_logro_id ?? 0);
                }
                $ie->$nivel_logro_id = round(intval($ie?->$nivel_logro_id ?? 0) / $sumatoria * 100, 2);
                $ie->total = $sumatoria;
            }
        }

        $nro_preguntas = count($matriz);

        gc_collect_cycles();

        $pdf = App::make('snappy.pdf.wrapper');

        $htmlcontent = view('ere.ere_resultados_pdf', compact('resultados', 'resumen', 'matriz', 'nro_preguntas', 'filtros', 'niveles', 'pdf', 'ies'))->render();
        // $pdf->loadView('ere.pdf.resultados', compact('resultados', 'resumen', 'matriz', 'nro_preguntas', 'filtros', 'niveles', 'pdf', 'ies'))->setPaper('a4', 'landscape');

        $headerHtml = view('ere.ere_resultados_pdf_header', compact('filtros'))->render();
        $footerHtml = view('ere.ere_resultados_pdf_footer', compact('filtros'))->render();
        // $footerHtml = view()->make('pdf.footer')->render();

        $pdf->loadHtml($htmlcontent)
            ->setPaper('a4', 'landscape')
            ->setOption('disable-external-links', true)
            ->setOption('enable-local-file-access', true)
            ->setOption('disable-smart-shrinking', true)
            ->setOption('margin-top', '3cm')
            ->setOption('margin-bottom', '2cm')
            ->setOption('footer-left', "PAGINA [page] DE [toPage]")
            ->setOption('footer-font-size', 8)
            ->setOption('header-html', $headerHtml)
            ->setOption('footer-html', $footerHtml)
            ->setOption('dpi', 300);

        return $pdf->stream('RESULTADOS-ERE-'.date('Ymdhis').'.pdf');

        // return view('ere.pdf.resultados', compact('resultados', 'resumen', 'matriz', 'nro_preguntas', 'filtros', 'niveles', 'pdf', 'ies'));

    }

    public function generarExcel(Request $request)
    {
        $parametros = [
            $request->iYAcadId,
            $request->iEvaluacionId,
            $request->iCursoId,
            $request->iNivelTipoId,
            $request->iNivelGradoId,
            $request->iSeccionId,
            $request->iDsttId,
            $request->cPersSexo,
            $request->iUgelId,
            $request->iIieeId,
            $request->iSedeId,
            $request->iTipoSectorId,
            $request->iZonaId,
            $request->iCredEntPerfId,
            1, // Mostrar detalle en EXCEL
            $request->cTipoReporte,
        ];

        try {
            $data = DB::selectResultSets('EXEC ere.SP_SEL_evaluacionInformeResumenOpt ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'mensaje' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'mensaje' => $error_message];
            $codeResponse = 500;
            return response()->json($response, $codeResponse);
        }

        $filtros = $data[0][0];
        $resultados = $data[1];
        $niveles = $data[2];
        $resumen = $this->convertDataToChartForm($data[3]);
        $matriz = $data[4];
        $ies = null;

        foreach ( $resultados as $resultado) {
            $resultado->respuestas = json_decode($resultado->respuestas);
        }
        if( $filtros->tipo_reporte == 'IE' ) {
            $ies = $data[5];
            foreach ( $ies as $ie) {
                $sumatoria = 0;
                foreach( $niveles as $nivel) {
                    $nivel_logro_id = strval($nivel?->nivel_logro_id ?? '');
                    $sumatoria += intval($ie?->$nivel_logro_id ?? 0);
                }
                $ie->$nivel_logro_id = round(intval($ie?->$nivel_logro_id ?? 0) / $sumatoria * 100, 2);
                $ie->total = $sumatoria;
            }
        }

        $nro_preguntas = count($matriz);

        return view('ere.ere_resultados_excel', compact('resultados', 'resumen', 'matriz', 'nro_preguntas', 'filtros', 'niveles', 'ies'));
    }

    public function obtenerInformeComparacion(Request $request)
    {
        $parametros = [
            $request->iYAcadId,
            $request->iEvaluacion1,
            $request->iEvaluacion2,
            $request->iCursoId,
            $request->iNivelTipoId,
            $request->iNivelGradoId,
            $request->iSeccionId,
            $request->iDsttId,
            $request->cPersSexo,
            $request->iUgelId,
            $request->iIieeId,
            $request->iSedeId,
            $request->iTipoSectorId,
            $request->iZonaId,
            $request->iCredEntPerfId,
            0, // No mostrar detalle en vista
        ];

        try {
            $data = DB::selectResultSets('EXEC ere.SP_SEL_evaluacionInformeComparacion ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'mensaje' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'mensaje' => $error_message];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    public function obtenerInformeComparacionPdf(Request $request)
    {
        $parametros = [
            $request->iYAcadId,
            $request->iEvaluacion1,
            $request->iEvaluacion2,
            $request->iCursoId,
            $request->iNivelTipoId,
            $request->iNivelGradoId,
            $request->iSeccionId,
            $request->iDsttId,
            $request->cPersSexo,
            $request->iUgelId,
            $request->iIieeId,
            $request->iSedeId,
            $request->iTipoSectorId,
            $request->iZonaId,
            $request->iCredEntPerfId,
            1, // No mostrar detalle en vista
        ];

        try {
            $data = DB::selectResultSets('EXEC ere.SP_SEL_evaluacionInformeComparacion ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'mensaje' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'mensaje' => $error_message];
            $codeResponse = 500;
            return response()->json($response, $codeResponse);
        }
        $filtros = $data[0][0];
        $resultados1 = $data[1];
        $niveles1 = $data[2];
        $resultados2 = $data[3];
        $niveles2 = $data[4];

        foreach ( $resultados1 as $resultado) {
            $resultado->respuestas = json_decode($resultado->respuestas);
        }
        foreach ( $resultados2 as $resultado) {
            $resultado->respuestas = json_decode($resultado->respuestas);
        }

        $total1 = array_reduce($niveles1, function($sum, $item) {
            return $sum += intval($item->cantidad);
        });
        $total2 = array_reduce($niveles2, function($sum, $item) {
            return $sum += intval($item->cantidad);
        });

        $niveles = null;
        foreach( $niveles1 as $key => $nivel) {
            $niveles[] = [
                'nivel' => $nivel->nivel_logro,
                'cantidad1' => intval($nivel->cantidad),
                'porcentaje1' => $total1 == 0 ? 0 : round(intval($nivel->cantidad) / $total1 * 100, 2),
                'cantidad2' => intval($niveles2[$key]->cantidad),
                'porcentaje2' => $total2 == 0 ? 0 : round(intval($niveles2[$key]->cantidad) / $total2 * 100, 2),
            ];
        }

        gc_collect_cycles();

        $pdf = App::make('snappy.pdf.wrapper');

        $htmlcontent = view('ere.ere_comparacion_pdf', compact('resultados1', 'resultados2', 'niveles', 'filtros', 'pdf', 'total1', 'total2'))->render();
        // $pdf->loadView('ere.pdf.resultados', compact('resultados', 'resumen', 'matriz', 'nro_preguntas', 'filtros', 'niveles', 'pdf', 'ies'))->setPaper('a4', 'landscape');

        $headerHtml = view('ere.ere_comparacion_pdf_header', compact('filtros'))->render();
        $footerHtml = view('ere.ere_resultados_pdf_footer', compact('filtros'))->render();

        $pdf->loadHtml($htmlcontent)
            ->setPaper('a4', 'landscape')
            ->setOption('disable-external-links', true)
            ->setOption('enable-local-file-access', true)
            ->setOption('disable-smart-shrinking', true)
            ->setOption('margin-top', '3cm')
            ->setOption('margin-bottom', '2cm')
            ->setOption('footer-left', "PAGINA [page] DE [toPage]")
            ->setOption('footer-font-size', 8)
            ->setOption('header-html', $headerHtml)
            ->setOption('footer-html', $footerHtml)
            ->setOption('dpi', 300);

        return $pdf->stream('CONPARACION-ERE-'.date('Ymdhis').'.pdf');

        // return view('ere.pdf.resultados', compact('resultados1', 'resultados2', 'niveles', 'filtros', 'pdf', 'total1', 'total2'));
    }

    public function obtenerInformeComparacionExcel(Request $request)
    {
        $parametros = [
            $request->iYAcadId,
            $request->iEvaluacion1,
            $request->iEvaluacion2,
            $request->iCursoId,
            $request->iNivelTipoId,
            $request->iNivelGradoId,
            $request->iSeccionId,
            $request->iDsttId,
            $request->cPersSexo,
            $request->iUgelId,
            $request->iIieeId,
            $request->iSedeId,
            $request->iTipoSectorId,
            $request->iZonaId,
            $request->iCredEntPerfId,
            1, // No mostrar detalle en vista
        ];

        try {
            $data = DB::selectResultSets('EXEC ere.SP_SEL_evaluacionInformeComparacion ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'mensaje' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'mensaje' => $error_message];
            $codeResponse = 500;
            return response()->json($response, $codeResponse);
        }

        $filtros = $data[0][0];
        $resultados1 = $data[1];
        $niveles1 = $data[2];
        $resultados2 = $data[3];
        $niveles2 = $data[4];

        foreach ( $resultados1 as $resultado) {
            $resultado->respuestas = json_decode($resultado->respuestas);
        }
        foreach ( $resultados2 as $resultado) {
            $resultado->respuestas = json_decode($resultado->respuestas);
        }

        $total1 = array_reduce($niveles1, function($sum, $item) {
            return $sum += intval($item->cantidad);
        });
        $total2 = array_reduce($niveles2, function($sum, $item) {
            return $sum += intval($item->cantidad);
        });

        $niveles = null;
        foreach( $niveles1 as $key => $nivel) {
            $niveles[] = [
                'nivel' => $nivel->nivel_logro,
                'cantidad1' => intval($nivel->cantidad),
                'porcentaje1' => $total1 == 0 ? 0 : round( intval($nivel->cantidad) / $total1 * 100, 2),
                'cantidad2' => intval($niveles2[$key]->cantidad),
                'porcentaje2' => $total2 == 0 ? 0 : round(intval($niveles2[$key]->cantidad) / $total2 * 100, 2),
            ];
        }

        return view('ere.ere_comparacion_excel', compact('resultados1', 'resultados2', 'niveles', 'filtros', 'total1', 'total2'));
    }

    private function convertDataToChartForm($data)
    {
        $newData = array();
        $firstLine = true;

        foreach ($data as $dataRow) {
            if ($firstLine) {
                $newData[] = array_keys((array) $dataRow);
                $firstLine = false;
            }
            $newData[] = array_values((array) $dataRow);
        }
        return $newData;
    }

    private function calcularResumenNiveles($data)
    {
        return array_reduce($data, function($acc = [], $item) {
            $nivel = $item->nivel_logro;
            if (isset($acc[$nivel])) {
                $acc[$nivel]++;
            } else {
                $acc[$nivel] = 1;
            }
            return $acc;
        });
    }
}
