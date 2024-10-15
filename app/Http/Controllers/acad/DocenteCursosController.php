<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class DocenteCursosController extends Controller
{
    protected $hashids;
    protected $idDocCursoId;
    protected $iSemAcadId;
    protected $iYAcadId;
    protected $iDocenteId;
    protected $iIeCursoId;

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
        if ($request->iSemAcadId) {
            $iSemAcadId = $this->hashids->decode($request->iSemAcadId);
            $iSemAcadId = count($iSemAcadId) > 0 ? $iSemAcadId[0] : $iSemAcadId;
        }
        if ($request->iYAcadId) {
            $iYAcadId = $this->hashids->decode($request->iYAcadId);
            $iYAcadId = count($iYAcadId) > 0 ? $iYAcadId[0] : $iYAcadId;
        }
        if ($request->iDocenteId) {
            $iDocenteId = $this->hashids->decode($request->iDocenteId);
            $iDocenteId = count($iDocenteId) > 0 ? $iDocenteId[0] : $iDocenteId;
        }
        if ($request->iIeCursoId) {
            $iIeCursoId = $this->hashids->decode($request->iIeCursoId);
            $iIeCursoId = count($iIeCursoId) > 0 ? $iIeCursoId[0] : $iIeCursoId;
        }

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $idDocCursoId                       ?? NULL,
            $iSemAcadId                         ?? NULL,
            $iYAcadId                           ?? NULL,
            $iDocenteId                         ?? NULL,
            $iIeCursoId                         ?? NULL,
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
                $value->iSemAcadId = $this->hashids->encode($value->iSemAcadId);
                $value->iYAcadId = $this->hashids->encode($value->iYAcadId);
                $value->iIeCursoId = $this->hashids->encode($value->iIeCursoId);
                $value->iSilaboId = $this->hashids->encode(($value->iSilaboId));
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
