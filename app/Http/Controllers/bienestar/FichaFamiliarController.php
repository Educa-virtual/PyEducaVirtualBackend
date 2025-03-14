<?php

namespace App\Http\Controllers\bienestar;

use App\Http\Controllers\Controller;
use App\Services\ParseSqlErrorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FichaFamiliarController extends Controller
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

    public function save(Request $request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->iPersId,
            $request->iTipoFamiliarId,
            $request->bFamiliarVivoConEl,
            $request->iTipoIdentId,
            $request->cPersDocumento,
            $request->cPersNombre,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->dPersNacimiento,
            $request->cPersSexo,
            $request->iTipoEstCivId,
            $request->iNacionId,
            $request->iDptoId,
            $request->iPrvnId,
            $request->iDsttId,
            $request->cPersDomicilio,
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
            $request->iOcupacionId,
            $request->iGradoInstId,
            $request->iTipoIeEstId,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_INS_fichaFamiliar ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
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
}
