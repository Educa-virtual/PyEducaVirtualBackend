<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class TipoBibliografiasController extends Controller
{
    protected $hashids;
    protected $iTipoBiblioId;
   

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
        if ($request->iTipoBiblioId) {
            $iTipoBiblioId = $this->hashids->decode($request->iTipoBiblioId);
            $iTipoBiblioId = count($iTipoBiblioId) > 0 ? $iTipoBiblioId[0] : $iTipoBiblioId;
        }


        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iTipoBiblioId                         ?? NULL,
            $request->cTipoBiblioNombre            ?? NULL,

            $request->iCredId

        ];

        try {
            $data = DB::select('exec acad.Sp_ACAD_CRUD_TIPO_BIBLIOGRAFIAS
                ?,?,?,?,?', $parametros);

            foreach ($data as $key => $value) {
                $value->iTipoBiblioId = $this->hashids->encode($value->iTipoBiblioId);
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
