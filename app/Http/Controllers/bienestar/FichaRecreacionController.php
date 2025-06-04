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
    public function guardarFichaRecreacion(FichaRecreacionSaveRequest $request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->cFichaDGPerteneceLigaDeportiva,
            $request->cFichaDGPerteneceCentroArtistico,
            $request->cFichaDGAsistioConsultaPsicologica,
            $request->jsonDeportes,
            $request->jsonTransportes,
            $request->jsonPasatiempos,
            $request->jsonProblemas,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_INS_fichaRecreacion ?,?,?,?,?,?,?,?', $parametros);
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

    public function actualizarFichaRecreacion(Request $request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->cFichaDGPerteneceLigaDeportiva,
            $request->cFichaDGPerteneceCentroArtistico,
            $request->cFichaDGAsistioConsultaPsicologica,
            $request->jsonDeportes,
            $request->jsonTransportes,
            $request->jsonPasatiempos,
            $request->jsonProblemas,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_UPD_fichaRecreacion ?,?,?,?,?,?,?,?', $parametros);
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

    public function verFichaRecreacion(Request $request)
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
