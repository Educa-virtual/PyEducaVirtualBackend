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
        $request['valorBusqueda'] = is_null($request->valorBusqueda)
            ? null
            : (is_numeric($request->valorBusqueda)
                ? $request->valorBusqueda
                : ($this->hashids->decode($request->valorBusqueda)[0] ?? null));

        $request['idDocCursoId'] = is_null($request->idDocCursoId)
            ? null
            : (is_numeric($request->idDocCursoId)
                ? $request->idDocCursoId
                : ($this->hashids->decode($request->idDocCursoId)[0] ?? null));

        $request['iSemAcadId'] = is_null($request->iSemAcadId)
            ? null
            : (is_numeric($request->iSemAcadId)
                ? $request->iSemAcadId
                : ($this->hashids->decode($request->iSemAcadId)[0] ?? null));

        $request['iYAcadId'] = is_null($request->iYAcadId)
            ? null
            : (is_numeric($request->iYAcadId)
                ? $request->iYAcadId
                : ($this->hashids->decode($request->iYAcadId)[0] ?? null));

        $request['iDocenteId'] = is_null($request->iDocenteId)
            ? null
            : (is_numeric($request->iDocenteId)
                ? $request->iDocenteId
                : ($this->hashids->decode($request->iDocenteId)[0] ?? null));

        $request['iIeCursoId'] = is_null($request->iIeCursoId)
            ? null
            : (is_numeric($request->iIeCursoId)
                ? $request->iIeCursoId
                : ($this->hashids->decode($request->iIeCursoId)[0] ?? null));

        
        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->idDocCursoId                       ?? NULL,
            $request->iSemAcadId                         ?? NULL,
            $request->iYAcadId                           ?? NULL,
            $request->iDocenteId                         ?? NULL,
            $request->iIeCursoId                         ?? NULL,
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
                $value->iCursoId = $this->hashids->encode(($value->iCursoId));
                $value->iNivelGradoId = $this->hashids->encode(($value->iNivelGradoId));
                $value->iSeccionId = $this->hashids->encode(($value->iSeccionId));
                //$value->iGradoId = $this->hashids->encode(($value->iGradoId));
                //$value->iDocenteId = $this->hashids->encode(($value->iDocenteId));
                if (isset($value->iGradoId)) {
                    $this->hashids->encode(($value->iGradoId));
                }
                if (isset($value->iDocenteId)) {
                    $this->hashids->encode(($value->iDocenteId));
                }
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
