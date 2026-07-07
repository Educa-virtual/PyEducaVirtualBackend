<?php

namespace App\Http\Controllers\doc;

use App\Http\Controllers\Controller;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Cursos extends Controller
{
    protected $hashids;

    protected $iCursoId;

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
        if ($request->iCursoId) {
            $iCursoId = $this->hashids->decode($request->iCursoId);
            $iCursoId = count($iCursoId) > 0 ? $iCursoId[0] : $iCursoId;
        }

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iCursoId ?? null,
            $request->iCurrId ?? null,
            $request->iTipoCursoId ?? null,
            $request->cCursoNombre ?? null,
            $request->nCursoCredTeoria ?? null,
            $request->nCursoCredPractica ?? null,
            $request->cCursoDescripcion ?? null,
            $request->nCursoTotalCreditos ?? null,
            $request->cCursoPerfilDocente ?? null,
            $request->iCursoTotalHoras ?? null,
            $request->iCursoEstado ?? null,
            $request->iEstado ?? null,
            $request->iSesionId ?? null,

            $request->iCredId,

        ];

        try {
            $data = DB::select('exec acad.Sp_ACAD_CRUD_CURSOS
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }
}
