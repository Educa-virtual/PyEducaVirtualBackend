<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class TareaCabeceraGruposController extends Controller
{
    protected $hashids;

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
        if ($request->iProgActId) {
            $iProgActId = $this->hashids->decode($request->iProgActId);
            $iProgActId = count($iProgActId) > 0 ? $iProgActId[0] : $iProgActId;
        }

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

    
            //$request->iCredId

        ];

        try {
            $data = DB::select('exec aula.Sp_AULA_CRUD_TAREA_CABECERA_GRUPOS
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            foreach ($data as $key => $value) {
                $value->iProgActId = $this->hashids->encode($value->iProgActId);
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
