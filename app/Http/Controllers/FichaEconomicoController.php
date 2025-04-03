<?php

namespace App\Http\Controllers;

use App\Services\ParseSqlErrorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FichaEconomicoController extends Controller
{
    public function save(Request $request)
    {
        $parametros = [
            $request->iCredId,
            $request->iFichaDGId,
            $request->iIngresoEcoFamiliar,
            $request->cIngresoEcoActividad,
            $request->iIngresoEcoEstudiante,
            $request->iIngresoEcoTrabajoHoras,
            $request->bIngresoEcoTrabaja,
            $request->cIngresoEcoDependeDe,
            $request->iRangoSueldoId,
            $request->iRangoSueldoIdPersona,
            $request->iDepEcoId,
            $request->iTipoAEcoId,
            $request->iJorTrabId,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_INS_fichaEconomico ?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
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
            $request->iCredId,
            $request->iIngresoEcoId,
            $request->iFichaDGId,
            $request->iIngresoEcoFamiliar,
            $request->cIngresoEcoActividad,
            $request->iIngresoEcoEstudiante,
            $request->iIngresoEcoTrabajoHoras,
            $request->bIngresoEcoTrabaja,
            $request->cIngresoEcoDependeDe,
            $request->iRangoSueldoId,
            $request->iRangoSueldoIdPersona,
            $request->iDepEcoId,
            $request->iTipoAEcoId,
            $request->iJorTrabId,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_UPD_fichaEconomico ?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
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
            $data = DB::select('EXEC obe.Sp_SEL_fichaEconomico ?', $parametros);
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
