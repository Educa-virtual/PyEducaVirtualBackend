<?php

namespace App\Http\Controllers\api\grl;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PersonaController extends Controller
{
    public function __invoke(Request $request)
    {
        return Pdf::loadView('imprimir', ['invoice' => 12312])
            ->setPaper('a4', 'landscape')
            ->name('your-invoice.pdf');
    }
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

        $query=DB::select("execute grl.Sp_SEL_personas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?",[$solicitud]);

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

    public function guardarPersona(Request $request){

        $request->merge([
            'iTipoPersId' => 1, // Siempre persona natural
        ]);
        $parametros = $this->validateGuardarPersona($request);

        try {
            $data = DB::select('execute grl.Sp_INS_personas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    private function validateGuardarPersona(Request $request){
        return $request->validate([
            'iTipoPersId' => 'required|integer',
            'iTipoIdentId' => 'required|integer',
            'cPersDocumento' => 'required|string|min:8|max:15',
            'cPersPaterno' => 'nullable|string|max:50',
            'cPersMaterno' => 'nullable|string|max:50',
            'cPersNombre' => 'required|string|max:50',
            'cPersSexo' => 'required|size:1',
            'dPersNacimiento' => 'nullable|date',
            'iTipoEstCivId' => 'nullable',
            'iNacionId' => 'nullable|integer',
            'iPersRepresentanteLegalId' => 'nullable|integer',
            'cPersDomicilio' => 'nullable|string',
            'iPaisId' => 'nullable|integer',
            'iDptoId' => 'nullable|integer',
            'iPrvnId' => 'nullable|integer',
            'iDsttId' => 'nullable|integer',
        ]);
    }
}
