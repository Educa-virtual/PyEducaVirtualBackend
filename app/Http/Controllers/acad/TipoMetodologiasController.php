<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoMetodologiasController extends Controller
{
    protected $hashids;

    protected $iTipoMetId;

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
        if ($request->iTipoMetId) {
            $iTipoMetId = $this->hashids->decode($request->iTipoMetId);
            $iTipoMetId = count($iTipoMetId) > 0 ? $iTipoMetId[0] : $iTipoMetId;
        }

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',
            $iTipoMetId ?? null,
            $request->cTipoMetNombre ?? null,
            $request->iCredId,

        ];

        try {
            $data = DB::select('exec acad.Sp_SEL_tipoMetodologias
                ?,?,?,?,?', $parametros);

            foreach ($data as $key => $value) {
                $value->iTipoMetId = $this->hashids->encode($value->iTipoMetId);
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
