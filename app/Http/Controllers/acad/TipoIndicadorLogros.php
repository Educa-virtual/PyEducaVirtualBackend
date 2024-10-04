<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class TipoIndicadorLogros extends Controller
{
    protected $hashids;
    protected $iTipoIndLogId;


    public function __construct()
    {
        $this->hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
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
        if ($request->iTipoIndLogId) {
            $iTipoIndLogId = $this->hashids->decode($request->iTipoIndLogId);
            $iTipoIndLogId = count($iTipoIndLogId) > 0 ? $iTipoIndLogId[0] : $iTipoIndLogId;
        }


        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iTipoIndLogId                      ?? NULL,
            $request->cTipoIndLogNombre         ?? NULL,
            $request->bTipoIndLogReqDetalle     ?? NULL,

            $request->iCredId

        ];

        try {
            $data = DB::select('exec acad.Sp_ACAD_CRUD_TIPO_INDICADOR_LOGROS
                ?,?,?,?,?,?', $parametros);

            foreach ($data as $key => $value) {
                $value->iTipoIndLogId = $this->hashids->encode($value->iTipoIndLogId);
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
