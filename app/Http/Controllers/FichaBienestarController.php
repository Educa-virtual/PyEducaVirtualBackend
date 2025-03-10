<?php

namespace App\Http\Controllers;

use App\Services\ParseSqlErrorService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FichaBienestarController extends Controller
{
    public function index(Request $request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->iPersId,
            $request->iFamiliarId,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_SEL_fichasFamiliaresPersonas ?,?,?', $parametros);
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
