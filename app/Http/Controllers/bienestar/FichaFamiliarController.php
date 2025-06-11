<?php

namespace App\Http\Controllers\bienestar;

use App\Http\Controllers\Controller;
use App\Http\Requests\bienestar\FichaFamiliarSaveRequest;
use App\Services\ParseSqlErrorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FichaFamiliarController extends Controller
{
    public function listarFichaFamiliares(Request $request)
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

    public function guardarFichaFamiliar(FichaFamiliarSaveRequest $request)
    {
        if( !$request->has('iPersId') || $request->iPersId == null ) {
            $request->merge([
                'iTipoPersId' => 1, // Siempre persona natural
            ]);

            $parametros = [
                $request->iTipoPersId,
                $request->iTipoIdentId,
                $request->cPersDocumento,
                $request->cPersPaterno,
                $request->cPersMaterno,
                $request->cPersNombre,
                $request->cPersSexo,
                $request->dPersNacimiento,
                $request->iTipoEstCivId,
                $request->cPersFotografia,
                $request->cPersRazonSocialNombre,
                $request->cPersRazonSocialCorto,
                $request->cPersRazonSocialSigla,
                $request->cPersDomicilio,
                $request->iSesionId,
                $request->iNacionId,
                $request->iPaisId,
                $request->iDptoId,
                $request->iPrvnId,
                $request->iDsttId,
            ];

            try {
                $data = DB::select('EXEC grl.Sp_INS_personas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            } catch (\Exception $e) {
                $error_message = ParseSqlErrorService::parse($e->getMessage());
                $response = ['validated' => false, 'message' => $error_message, 'data' => []];
                $codeResponse = 500;
                return new JsonResponse($response, $codeResponse);
            }

            $request->merge([
                'iPersId' => $data[0]->iPersId,
            ]);
        }

        // luego guardar como estudiante
        $parametros = [
            $request->iFichaDGId,
            $request->iPersId,
            $request->iTipoFamiliarId,
            $request->bFamiliarVivoConEl,
            $request->iTipoEstCivId,
            $request->iTipoViaId,
            $request->cFamiliarDireccionNombreVia,
            $request->cFamiliarDireccionNroPuerta,
            $request->cFamiliarDireccionBlock,
            $request->cFamiliarDireccionInterior,
            $request->iFamiliarDireccionPiso,
            $request->cFamiliarDireccionManzana,
            $request->cFamiliarDireccionLote,
            $request->cFamiliarDireccionKm,
            $request->cFamiliarDireccionReferencia,
            $request->iOcupacionId,
            $request->iGradoInstId,
            $request->iTipoIeEstId,
            $request->cTipoViaOtro,
            $request->cFamiliarResidenciaActual,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_INS_fichaFamiliar ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function actualizarFichaFamiliar(FichaFamiliarSaveRequest $request)
    {
        if( !$request->has('iPersId') || $request->iPersId == null ) {
            $request->merge([
                'iTipoPersId' => 1, // Siempre persona natural
            ]);

            $parametros = [
                $request->iTipoPersId,
                $request->iTipoIdentId,
                $request->cPersDocumento,
                $request->cPersPaterno,
                $request->cPersMaterno,
                $request->cPersNombre,
                $request->cPersSexo,
                $request->dPersNacimiento,
                $request->iTipoEstCivId,
                $request->cPersFotografia,
                $request->cPersRazonSocialNombre,
                $request->cPersRazonSocialCorto,
                $request->cPersRazonSocialSigla,
                $request->cPersDomicilio,
                $request->iSesionId,
                $request->iNacionId,
                $request->iPaisId,
                $request->iDptoId,
                $request->iPrvnId,
                $request->iDsttId,
            ];

            try {
                $data = DB::select('EXEC grl.Sp_INS_personas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            } catch (\Exception $e) {
                $error_message = ParseSqlErrorService::parse($e->getMessage());
                $response = ['validated' => false, 'message' => $error_message, 'data' => []];
                $codeResponse = 500;
                return new JsonResponse($response, $codeResponse);
            }

            $request->merge([
                'iPersId' => $data[0]->iPersId,
            ]);
        }

        // luego guardar como estudiante
        $parametros = [
            $request->iFamiliarId,
            $request->iFichaDGId,
            $request->iPersId,
            $request->iTipoFamiliarId,
            $request->bFamiliarVivoConEl,
            $request->iTipoEstCivId,
            $request->iTipoViaId,
            $request->cFamiliarDireccionNombreVia,
            $request->cFamiliarDireccionNroPuerta,
            $request->cFamiliarDireccionBlock,
            $request->cFamiliarDireccionInterior,
            $request->iFamiliarDireccionPiso,
            $request->cFamiliarDireccionManzana,
            $request->cFamiliarDireccionLote,
            $request->cFamiliarDireccionKm,
            $request->cFamiliarDireccionReferencia,
            $request->iOcupacionId,
            $request->iGradoInstId,
            $request->iTipoIeEstId,
            $request->cTipoViaOtro,
            $request->cFamiliarResidenciaActual,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_UPD_fichaFamiliar ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function verFichaFamiliar(Request $request)
    {
        $parametros = [
            $request->iFamiliarId,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_SEL_fichaFamiliar ?', $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }

    public function borrarFichaFamiliar(Request $request)
    {
        $parametros = [
            $request->iFamiliarId,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_DEL_fichaFamiliar ?', $parametros);
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
