<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;

class EstudiantesController extends Controller
{
    protected $hashids;
    protected $iEstudianteId;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function obtenerCursosXEstudianteAnioSemestre(Request $request)
    {
        $request->validate(
            [
                'iEstudianteId' => 'required',
                'iYearId' => 'required',
            ],
            [
                'iEstudianteId.required' => 'Hubo un problema al obtener el iEstudianteId',
                'iYearId.required' => 'Hubo un problema al obtener el iYearId',
            ]
        );

        $parametros = [
            $request->iEstudianteId,
            $request->iYearId
        ];

        try {
            $data = DB::select("execute acad.Sp_SEL_cursosXEstudianteAnioSemestre ?,?", $parametros);

            foreach ($data as $key => $value) {
                $value->iCursoId = $this->hashids->encode($value->iCursoId);
                $value->iSilaboId = $this->hashids->encode($value->iSilaboId);
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
