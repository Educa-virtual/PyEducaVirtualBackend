<?php

namespace App\Http\Controllers\grl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;

class PersonasController extends Controller
{
    protected $hashids;
    protected $iPersId;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function list(Request $request)
    {
        $request->validate(
            [
                'opcion' => 'required',
            ],
            [
                'opcion.required' => 'Hubo un problema al obtener la acción',
            ]
        );
        if ($request->iPersId) {
            $iPersId = $this->hashids->decode($request->iPersId);
            $iPersId = count($iPersId) > 0 ? $iPersId[0] : $request->iPersId;
        }

        $parametros = [
            $request->opcion,
            $iPersId                            ?? NULL,
            $request->iTipoPersId               ?? NULL,
            $request->iTipoIdentId              ?? NULL,
            $request->cPersDocumento            ?? NULL,
            $request->cPersPaterno              ?? NULL,
            $request->cPersMaterno              ?? NULL,
            $request->cPersNombre               ?? NULL,
            $request->cPersSexo                 ?? NULL,
            $request->dPersNacimiento           ?? NULL,
            $request->iTipoEstCivId             ?? NULL,
            $request->iNacionId                 ?? NULL,
            $request->cPersFotografia           ?? NULL,
            $request->cPersRazonSocialNombre    ?? NULL,
            $request->cPersRazonSocialCorto     ?? NULL,
            $request->cPersRazonSocialSigla     ?? NULL,
            $request->iPersRepresentanteLegalId ?? NULL,
            $request->cPersDomicilio            ?? NULL,
            $request->iTipoSectorId             ?? NULL,
            $request->iPaisId                   ?? NULL,
            $request->iDptoId                   ?? NULL,
            $request->iPrvnId                   ?? NULL,
            $request->iDsttId                   ?? NULL,
            $request->iPersEstado               ?? NULL,
            $request->cPersCodigoVerificacion   ?? NULL,
            $request->cPersObs                  ?? NULL
        ];

        try {
            $data = DB::select("execute grl.Sp_CRUD_persona ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?", [$parametros]);

            foreach ($data as $key => $value) {
                $value->iPersId = $this->hashids->encode($value->iPersId);
            }

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);

    }
}
