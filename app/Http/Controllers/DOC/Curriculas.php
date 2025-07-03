<?php

namespace App\Http\Controllers\doc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class Curriculas extends Controller
{
    protected $hashids;
    
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
        
        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            
            
            $request->iCredId

        ];

        try {
            $data = DB::select('exec acad.Sp_ACAD_CRUD_DOCENTE_CURSOS
                ?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            
            foreach ($data as $key => $value) {
                $value->iCursoId = $this->hashids->encode($value->iCursoId);
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
