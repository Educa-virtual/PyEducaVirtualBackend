<?php

namespace App\Http\Controllers\bienestar;

use App\Http\Controllers\Controller;
use App\Http\Requests\FichaGeneralSaveRequest;
use App\Services\ParseSqlErrorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FichaViviendaController extends Controller
{
    public function save(FichaGeneralSaveRequest $request)
    {
        $parametros = [
            $request->iSesionId,
            $request->iFichaDGId,
            $request->iTipoOcupaVivId,
            $request->iMatPreId,
            $request->iTipoVivId,
            $request->iViviendaCarNroPisos,
            $request->iViviendaCarNroAmbientes,
            $request->iViviendaCarNroHabitaciones,
            $request->iEstadoVivId,
            $request->iMatPisoVivId,
            $request->iMatTecVivId,
            $request->iTiposSsHhId,
            $request->iTipoSumAId,
            $request->iTipoAlumId,
            $request->iEleParaVivId,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_INS_fichaVivienda ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
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
            $request->iSesionId,
            $request->iViendaCarId,
            $request->iFichaDGId,
            $request->iTipoOcupaVivId,
            $request->iMatPreId,
            $request->iTipoVivId,
            $request->iViviendaCarNroPisos,
            $request->iViviendaCarNroAmbientes,
            $request->iViviendaCarNroHabitaciones,
            $request->iEstadoVivId,
            $request->iMatPisoVivId,
            $request->iMatTecVivId,
            $request->iTiposSsHhId,
            $request->iTipoSumAId,
            $request->iTipoAlumId,
            $request->iEleParaVivId,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_UPD_fichaVivienda ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
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
            $data = DB::select('EXEC obe.Sp_SEL_fichaVivienda ?', $parametros);
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
