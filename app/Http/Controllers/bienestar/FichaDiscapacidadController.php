<?php

namespace App\Http\Controllers\bienestar;

use App\Http\Controllers\Controller;
use App\Services\ParseSqlErrorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FichaDiscapacidadController extends Controller
{
    public function guardarFichaDiscapacidad(Request $request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->bFichaDGEstaEnCONADIS,
            $request->cCodigoCONADIS,
            $request->bFichaDGEstaEnOMAPED,
            $request->cCodigoOMAPED,
            $request->cOtroProgramaDiscapacidad,
            $request->jsonDiscapacidades,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_UPD_fichaDiscapacidad ?,?,?,?,?,?,?', $parametros);
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

    public function actualizarFichaDiscapacidad(Request $request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->bFichaDGEstaEnCONADIS,
            $request->cCodigoCONADIS,
            $request->bFichaDGEstaEnOMAPED,
            $request->cCodigoOMAPED,
            $request->cOtroProgramaDiscapacidad,
            $request->jsonDiscapacidades,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_UPD_fichaDiscapacidad ?,?,?,?,?,?,?', $parametros);
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

    public function verFichaDiscapacidad(Request $request)
    {
        $parametros = [
            $request->iFichaDGId,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_SEL_fichaDiscapacidad ?', $parametros);
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
