<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class DocenteCursos extends Controller
{
    protected $hashids;
    protected $idDocCursoId;

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
        if ($request->idDocCursoId) {
            $idDocCursoId = $this->hashids->decode($request->idDocCursoId);
            $idDocCursoId = count($idDocCursoId) > 0 ? $idDocCursoId[0] : $idDocCursoId;
        } 
        
        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $idDocCursoId                       ?? NULL,
            $request->iSemAcadId                ?? NULL,
            $request->iYAcadId                  ?? NULL,
            $request->iDocenteId                ?? NULL,
            $request->iIeCursoId                ?? NULL,
            $request->cDocCursoObservaciones    ?? NULL,
            $request->iDocCursoHorasLectivas    ?? NULL,
            $request->iEstado                   ?? NULL,
            $request->iSesionId                 ?? NULL,

            $request->iCredId

        ];

        try {
            $data = DB::select('exec acad.Sp_ACAD_CRUD_DOCENTE_CURSOS
                ?,?,?,?,?,?,?,?,?,?,?,?', $parametros);


            foreach ($data as $key => $value) {
                $value->idDocCursoId = $this->hashids->encode($value->idDocCursoId);
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
