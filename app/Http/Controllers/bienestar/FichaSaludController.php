<?php

namespace App\Http\Controllers\bienestar;

use App\Http\Controllers\Controller;
use App\Services\ParseSqlErrorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FichaSaludController extends Controller
{
    public function guardarFichaSalud(Request $request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->bFichaDGAlergiaMedicamentos,
            $request->cFichaDGAlergiaMedicamentos,
            $request->bFichaDGAlergiaOtros,
            $request->cFichaDGAlergiaOtros,
            $request->jsonSeguros,
            $request->jsonDolencias,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_INS_fichaSalud ?,?,?,?,?,?,?', $parametros);
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

    public function actualizarFichaSalud(Request $request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->bFichaDGAlergiaMedicamentos,
            $request->cFichaDGAlergiaMedicamentos,
            $request->bFichaDGAlergiaOtros,
            $request->cFichaDGAlergiaOtros,
            $request->jsonSeguros,
            $request->jsonDolencias,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_UPD_fichaSalud ?,?,?,?,?,?,?', $parametros);
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

    public function verFichaSalud(Request $request)
    {
        $parametros = [
            $request->iFichaDGId,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_SEL_fichaSalud ?', $parametros);
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
