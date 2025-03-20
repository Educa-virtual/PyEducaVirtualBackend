<?php

namespace App\Http\Controllers\Ere;

use App\Http\Controllers\Controller;
use App\Services\ParseSqlErrorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportesController extends Controller
{
    public function obtenerEvaluacionesCursosIes(Request $request)
    {
        $parametros = [
            $request->iSesionId,
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
            $data = DB::select('EXEC ere.Sp_SEL_evaluacionesCursosIeGradosSecciones ?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'mensaje' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'mensaje' => $e->getMessage()];
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
        ];

        try {
            $data = DB::selectResultSets('EXEC ere.SP_SEL_evaluacionInformeResumen ?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'mensaje' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'mensaje' => $e->getMessage()];
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
        ];

        try {
            $data = DB::selectResultSets('EXEC ere.SP_SEL_evaluacionInformeResumen ?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'mensaje' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'mensaje' => $e->getMessage()];
            $codeResponse = 500;
            return response()->json($response, $codeResponse);
        }

        $resultados = $data[0];
        $resumen = $data[1];
        $matriz = $data[2];

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('ere.pdf.resultados', compact('resultados', 'resumen', 'matriz'))->setPaper('a4', 'landscape');
        return $pdf->stream('RESULTADOS-ERE-'.date('Ymdhis').'.pdf');

        // return view('ere.pdf.resultados', compact('resultados', 'resumen', 'matriz', 'aciertos', 'desaciertos', 'blancos'));

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
        ];

        try {
            $data = DB::selectResultSets('EXEC ere.SP_SEL_evaluacionInformeResumen ?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'mensaje' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'mensaje' => $e->getMessage()];
            $codeResponse = 500;
            return response()->json($response, $codeResponse);
        }

        $resultados = $data[0];
        $resumen = $this->convertDataToChartForm($data[1]);
        $matriz = $this->convertDataToChartForm($data[2]);

        return view('ere.excel.resultados', compact('resultados', 'resumen', 'matriz'));
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
}
