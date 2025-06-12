<?php

namespace App\Http\Controllers\bienestar;

use App\Http\Controllers\Controller;
use App\Services\ParseSqlErrorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecordarioFechasController extends Controller
{
    public function verFechasEspeciales(Request $request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iYAcadId,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_SEL_cumpleanios ?,?', $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }

    public function verConfRecordatorio(Request $request)
    {
        $parametros = [
            $request->iCredEntPerfId,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_SEL_cumpleaniosConfiguracion ?', $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }

    public function actualizarConfRecordatorio(Request $request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iPeriodoId,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_UPD_cumpleaniosConfiguracion ?,?', $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }
}
