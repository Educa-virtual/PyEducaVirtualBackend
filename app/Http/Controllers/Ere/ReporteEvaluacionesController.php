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
        $ies = null;

        foreach ( $resultados as $resultado) {
            $resultado->respuestas = json_decode($resultado->respuestas);
        }
        if( $filtros->tipo_reporte == 'IE' ) {
            $ies = $data[5];
            foreach ( $ies as $ie) {
                $sumatoria = 0;
                foreach( $niveles as $nivel) {
                    $nivel_logro_id = $nivel->nivel_logro_id . '';
                    $sumatoria += $ie->$nivel_logro_id;
                }
                foreach( $niveles as $nivel) {
                    $nivel_logro_id = $nivel->nivel_logro_id . '';
                    $ie->$nivel_logro_id = round($ie->$nivel_logro_id / $sumatoria * 100, 2);
                }
                $ie->total = $sumatoria;
            }
        }

        $nro_preguntas = count($matriz);

        gc_collect_cycles();

        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView('ere.pdf.resultados', compact('resultados', 'resumen', 'matriz', 'nro_preguntas', 'filtros', 'niveles', 'pdf', 'ies'))->setPaper('a4', 'landscape');
        return $pdf->stream('RESULTADOS-ERE-'.date('Ymdhis').'.pdf');

        // return view('ere.pdf.resultados', compact('resultados', 'resumen', 'matriz', 'nro_preguntas', 'filtros', 'niveles', 'pdf', 'ies));

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
                    $nivel_logro_id = $nivel->nivel_logro_id . '';
                    $sumatoria += $ie->$nivel_logro_id;
                }
                foreach( $niveles as $nivel) {
                    $nivel_logro_id = $nivel->nivel_logro_id . '';
                    $ie->$nivel_logro_id = round($ie->$nivel_logro_id / $sumatoria * 100, 2);
                }
                $ie->total = $sumatoria;
            }
        }

        $nro_preguntas = count($matriz);

        return view('ere.excel.resultados', compact('resultados', 'resumen', 'matriz', 'nro_preguntas', 'filtros', 'niveles', 'ies'));
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
