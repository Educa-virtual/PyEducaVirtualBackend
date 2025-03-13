<?php

namespace App\Http\Controllers\bienestar;

use App\Http\Controllers\Controller;
use App\Services\ParseSqlErrorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FichaGeneralController extends Controller
{
    public function createGeneral(Request $request)
    {
        try {
            $data = DB::select('EXEC obe.Sp_SEL_fichaGeneralParametros');
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

    public function saveGeneral(Request $request)
    {
        $parametros = [
            $request->iSesionId,
            $request->iPersId,
            $request->iTipoViaId,
            $request->cFichaDGDireccionNombreVia,
            $request->cFichaDGDireccionNroPuerta,
            $request->cFichaDGDireccionBlock,
            $request->cFichaDGDirecionInterior,
            $request->cFichaDGDirecionPiso,
            $request->cFichaDGDireccionManzana,
            $request->cFichaDGDireccionLote,
            $request->cFichaDGDireccionKm,
            $request->cFichaDGDireccionReferencia,
            $request->iReligionId,
            $request->bFamiliarPadreVive,
            $request->bFamiliarMadreVive,
            $request->bFamiliarPadresVivenJuntos,
            $request->bFichaDGTieneHijos,
            $request->iFichaDGNroHijos,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_INS_fichaGeneral ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
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

    public function updateGeneral(Request $request)
    {
        $parametros = [
            $request->iSesionId,
            $request->iFichaDGId,
            $request->iPersId,
            $request->iTipoViaId,
            $request->cFichaDGDireccionNombreVia,
            $request->cFichaDGDireccionNroPuerta,
            $request->cFichaDGDireccionBlock,
            $request->cFichaDGDirecionInterior,
            $request->cFichaDGDirecionPiso,
            $request->cFichaDGDireccionManzana,
            $request->cFichaDGDireccionLote,
            $request->cFichaDGDireccionKm,
            $request->cFichaDGDireccionReferencia,
            $request->iReligionId,
            $request->bFamiliarPadreVive,
            $request->bFamiliarMadreVive,
            $request->bFamiliarPadresVivenJuntos,
            $request->bFichaDGTieneHijos,
            $request->iFichaDGNroHijos,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_UPD_fichaGeneral ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
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

    public function showGeneral(Request $request)
    {
        $parametros = [
            $request->iFichaDGId,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_SEL_fichaGeneral ?', $parametros);
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
