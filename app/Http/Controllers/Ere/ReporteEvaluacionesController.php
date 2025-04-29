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
            $data = DB::select('EXEC ere.Sp_SEL_evaluacionesInforme ?,?,?,?,?,?,?,?,?,?', $parametros);
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
        ];

        try {
            $data = DB::selectResultSets('EXEC ere.SP_SEL_evaluacionInformeResumen ?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
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
        ];

        try {
            $data = DB::selectResultSets('EXEC ere.SP_SEL_evaluacionInformeResumen ?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
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

        $nro_preguntas = count($matriz);
        
        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView('ere.pdf.resultados', compact('resultados', 'resumen', 'matriz', 'nro_preguntas', 'filtros', 'niveles', 'pdf'))->setPaper('a4', 'landscape');
        return $pdf->stream('RESULTADOS-ERE-'.date('Ymdhis').'.pdf');

        // return view('ere.pdf.resultados', compact('resultados', 'resumen', 'matriz', 'nro_preguntas', 'filtros'));

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
        ];

        try {
            $data = DB::selectResultSets('EXEC ere.SP_SEL_evaluacionInformeResumen ?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
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

        $nro_preguntas = count($matriz);

        return view('ere.excel.resultados', compact('resultados', 'resumen', 'matriz', 'nro_preguntas', 'filtros', 'niveles'));
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
