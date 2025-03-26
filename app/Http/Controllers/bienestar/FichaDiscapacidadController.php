<?php

namespace App\Http\Controllers\bienestar;

use App\Http\Controllers\Controller;
use App\Services\ParseSqlErrorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FichaDiscapacidadController extends Controller
{
    public function save(Request $request)
    {
        $parametros = [
            $request->iSesionId,
            $request->iFichaDGId,
            $request->bFichaDGEstaEnCONADIS,
            $request->cCodigoCONADIS,
            $request->bFichaDGEstaEnOMAPED,
            $request->cCodigoOMAPED,
            $request->cOtroProgramaDiscapacidad,
            $request->bLimFisica,
            $request->cLimFisicaObs,
            $request->bLimSensorial,
            $request->cLimSensorialObs,
            $request->bLimIntelectual,
            $request->cLimIntelectualObs,
            $request->bLimMental,
            $request->cLimMentalObs,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_UPD_fichaAlimentacion ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
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
            $request->iFichaDGId,
            $request->iLugarAlimIdDesayuno,
            $request->cLugarAlimDesayuno,
            $request->iLugarAlimIdAlmuerzo,
            $request->cLugarAlimAlmuerzo,
            $request->iLugarAlimIdCena,
            $request->cLugarAlimCena,
            $request->iProAlimId,
            $request->cProAlimNombre,
            $request->bDietaVegetariana,
            $request->cDietaVegetarianaObs,
            $request->bDietaVegana,
            $request->cDietaVeganaObs,
            $request->bAlergiasAlim,
            $request->cAlergiasAlimObs,
            $request->bIntoleranciaAlim,
            $request->cIntoleranciaAlimObs,
            $request->bSumplementosAlim,
            $request->cSumplementosAlimObs,
            $request->bDificultadAlim,
            $request->cDificultadAlimObs,
            $request->cInfoAdicionalAlimObs,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_UPD_fichaAlimentacion ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
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
            $data = DB::select('EXEC obe.Sp_SEL_fichaAlimentacion ?', $parametros);
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
