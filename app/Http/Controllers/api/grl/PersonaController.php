<?php

namespace App\Http\Controllers\api\grl;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonaController extends Controller
{
    public function list(Request $request){
        $solicitud = [
        $request->opcion,
        $request->iPersId ?? NULL,
        $request->iTipoPersId ?? NULL,
        $request->iTipoIdentId ?? NULL,
        $request->cPersDocumento ?? NULL,
        $request->cPersPaterno ?? NULL,
        $request->cPersMaterno ?? NULL,
        $request->cPersNombre ?? NULL,
        $request->cPersSexo ?? NULL,
        $request->dPersNacimiento ?? NULL,
        $request->iTipoEstCivId ?? NULL,
        $request->iNacionId ?? NULL,
        $request->cPersFotografia ?? NULL,
        $request->cPersRazonSocialNombre ?? NULL,
        $request->cPersRazonSocialCorto ?? NULL,
        $request->cPersRazonSocialSigla ?? NULL,
        $request->iPersRepresentanteLegalId ?? NULL,
        $request->cPersDomicilio ?? NULL,
        $request->iTipoSectorId ?? NULL,
        $request->iPaisId ?? NULL,
        $request->iDptoId ?? NULL,
        $request->iPrvnId ?? NULL,
        $request->iDsttId ?? NULL,
        $request->iPersEstado ?? NULL,
        $request->cPersCodigoVerificacion ?? NULL,
        $request->cPersObs ?? NULL
        ];

        $query=DB::select("execute grl.Sp_CRUD_persona ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?",[$solicitud]);
        
        try{
            $response = [
                'validated' => true, 
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 200;

        } catch(Exception $e){
            $response = [
                'validated' => true, 
                'message' => $e->getMessage(),
                'data' => [],
            ];
            $estado = 500;
        }

        return new JsonResponse($response,$estado);
    }
}
