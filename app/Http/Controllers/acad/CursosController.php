<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class CursosController extends Controller
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

            $iCursoId                                              ??  NULL,
            $request->iCurrId                                      ??  NULL,
            $request->iTipoCursoId                                 ??  NULL,
            $request->cCursoNombre                                 ??  NULL,
            $request->nCursoCredTeoria                             ??  NULL,
            $request->nCursoCredPractica                           ??  NULL,
            $request->cCursoDescripcion                            ??  NULL,
            $request->nCursoTotalCreditos                          ??  NULL,
            $request->cCursoPerfilDocente                          ??  NULL,
            $request->iCursoTotalHoras                             ??  NULL,
            $request->iCursoEstado                                 ??  NULL,
            $request->iEstado                                      ??  NULL,
            $request->iSesionId                                    ??  NULL,

            $request->iCredId

        ];

        try {
            $data = DB::select('exec acad.Sp_SEL_cursos
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
