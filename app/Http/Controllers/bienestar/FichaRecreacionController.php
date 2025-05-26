<?php

namespace App\Http\Controllers\bienestar;

use App\Http\Controllers\Controller;
use App\Http\Requests\bienestar\FichaRecreacionSaveRequest;
use App\Services\ParseSqlErrorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FichaRecreacionController extends Controller
{
    public function save(FichaRecreacionSaveRequest $request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->cFichaDGPerteneceLigaDeportiva,
            $request->iDeporteId,
            $request->cDepFichaObs,
            $request->cFichaDGPerteneceCentroArtistico,
            $request->iPasaTiempoId,
            $request->cPasaTFichaHoras,
            $request->cFichaDGAsistioConsultaPsicologica,
            $request->cProbEFichaPresentePara,
            $request->iTipoFamiliarId,
            $request->iTransporteId,
            $request->nTransFichaGastoSoles,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_INS_fichaRecreacion ?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'se guardo la información', 'data' => $data];
            $codeResponse = 200;
        }
        catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }

    public function update(Request $request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->cFichaDGPerteneceLigaDeportiva,
            $request->iDeporteId,
            $request->cDepFichaObs,
            $request->cFichaDGPerteneceCentroArtistico,
            $request->iPasaTiempoId,
            $request->cPasaTFichaHoras,
            $request->cFichaDGAsistioConsultaPsicologica,
            $request->cProbEFichaPresentePara,
            $request->iTipoFamiliarId,
            $request->iTransporteId,
            $request->nTransFichaGastoSoles,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_UPD_fichaRecreacion ?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'se guardo la información', 'data' => $data];
            $codeResponse = 200;
        }
        catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }

    public function show(Request $request)
    {
        $parametros = [
            $request->iFichaDGId,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_SEL_fichaRecreacion ?', $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        }
        catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }
}
