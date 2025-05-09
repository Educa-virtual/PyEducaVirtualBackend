<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class RecursoDidacticosController extends Controller
{
    protected $hashids;
    protected $iRecDidacticoId;
   

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
        if ($request->iRecDidacticoId) {
            $iRecDidacticoId = $this->hashids->decode($request->iRecDidacticoId);
            $iRecDidacticoId = count($iRecDidacticoId) > 0 ? $iRecDidacticoId[0] : $iRecDidacticoId;
        }


        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',
            $iRecDidacticoId                         ?? NULL,
            $request->cRecDidacticoNombre            ?? NULL,
            $request->cRecDidacticoDescripcion       ?? NULL,
            $request->iCredId

        ];

        try {
            $data = DB::select('exec acad.Sp_SEL_recursoDidacticos
                ?,?,?,?,?,?', $parametros);

            foreach ($data as $key => $value) {
                $value->iRecDidacticoId = $this->hashids->encode($value->iRecDidacticoId);
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
