<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class TareasController extends Controller
{
    protected $hashids;
    protected $iTareaId;
    protected $iProgActId;
    protected $iDocenteId;


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
        if ($request->iTareaId) {
            $iTareaId = $this->hashids->decode($request->iTareaId);
            $iTareaId = count($iTareaId) > 0 ? $iTareaId[0] : $iTareaId;
        }
        if ($request->iProgActId) {
            $iProgActId = $this->hashids->decode($request->iProgActId);
            $iProgActId = count($iProgActId) > 0 ? $iProgActId[0] : $iProgActId;
        }
        if ($request->iDocenteId) {
            $iDocenteId = $this->hashids->decode($request->iDocenteId);
            $iDocenteId = count($iDocenteId) > 0 ? $iDocenteId[0] : $iDocenteId;
        }


        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iTareaId              ?? NULL,
            $iProgActId            ?? NULL,
            $iDocenteId            ?? NULL,
            $request->cTareaTitulo          ?? NULL,
            $request->cTareaDescripcion     ?? NULL,
            $request->cTareaArchivoAdjunto  ?? NULL,
            $request->cTareaIndicaciones    ?? NULL,
            $request->bTareaEsEvaluado      ?? NULL,
            $request->bTareaEsRestringida   ?? NULL,
            $request->bTareaEsGrupal        ?? NULL,
            $request->dtTareaInicio         ?? NULL,
            $request->dtTareaFin            ?? NULL,
            $request->cTareaComentarioDocente   ?? NULL,
            $request->iEstado                   ?? NULL,
            $request->iSesionId                 ?? NULL,
            $request->dtCreado                  ?? NULL,
            $request->dtActualizado             ?? NULL,

            //$request->iCredId

        ];

        try {
            $data = DB::select('exec aula.Sp_AULA_CRUD_TAREAS
            ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            foreach ($data as $key => $value) {
                $value->iTareaId = $this->hashids->encode($value->iTareaId);
                $value->iProgActId = $this->hashids->encode($value->iProgActId);
                $value->iDocenteId = $this->hashids->encode($value->iDocenteId);
            }

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'opcion' => 'required',
            ],
            [
                'opcion.required' => 'Hubo un problema al obtener la acción',
            ]
        );
        if ($request->iTareaId) {
            $iTareaId = $this->hashids->decode($request->iTareaId);
            $iTareaId = count($iTareaId) > 0 ? $iTareaId[0] : $iTareaId;
        }
        if ($request->iProgActId) {
            $iProgActId = $this->hashids->decode($request->iProgActId);
            $iProgActId = count($iProgActId) > 0 ? $iProgActId[0] : $iProgActId;
        }
        if ($request->iDocenteId) {
            $iDocenteId = $this->hashids->decode($request->iDocenteId);
            $iDocenteId = count($iDocenteId) > 0 ? $iDocenteId[0] : $iDocenteId;
        }


        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iTareaId              ?? NULL,
            $iProgActId,
            $iDocenteId            ?? NULL,
            $request->cTareaTitulo          ?? NULL,
            $request->cTareaDescripcion     ?? NULL,
            $request->cTareaArchivoAdjunto  ?? NULL,
            $request->cTareaIndicaciones    ?? NULL,
            $request->bTareaEsEvaluado      ?? NULL,
            $request->bTareaEsRestringida   ?? NULL,
            $request->bTareaEsGrupal        ?? NULL,
            $request->dtTareaInicio         ?? NULL,
            $request->dtTareaFin            ?? NULL,
            $request->cTareaComentarioDocente   ?? NULL,
            $request->iEstado                   ?? NULL,
            $request->iSesionId                 ?? NULL,
            $request->dtCreado                  ?? NULL,
            $request->dtActualizado             ?? NULL,

            //$request->iCredId

        ];
        //return $parametros;
        try {
            $data = DB::select('exec aula.Sp_AULA_CRUD_TAREAS
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            if ($data[0]->iTareaId > 0) {

                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function getTareasxiCursoId(Request $request)
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
            $iCursoId,
        ];

        try {
            $data = DB::select('exec aula.SP_Obtener_tareas
                ?', $parametros);

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

    public function delete(Request $request)
    {
        $request->validate(
            [
                'opcion' => 'required',
            ],
            [
                'opcion.required' => 'Hubo un problema al obtener la acción',
            ]
        );
        if ($request->iTareaId) {
            $iTareaId = $this->hashids->decode($request->iTareaId);
            $iTareaId = count($iTareaId) > 0 ? $iTareaId[0] : $iTareaId;
        }
        if ($request->iProgActId) {
            $iProgActId = $this->hashids->decode($request->iProgActId);
            $iProgActId = count($iProgActId) > 0 ? $iProgActId[0] : $iProgActId;
        }
        if ($request->iDocenteId) {
            $iDocenteId = $this->hashids->decode($request->iDocenteId);
            $iDocenteId = count($iDocenteId) > 0 ? $iDocenteId[0] : $iDocenteId;
        }


        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iTareaId              ?? NULL,
            $request->iProgActId,
            $iDocenteId            ?? NULL,
            $request->cTareaTitulo          ?? NULL,
            $request->cTareaDescripcion     ?? NULL,
            $request->cTareaArchivoAdjunto  ?? NULL,
            $request->cTareaIndicaciones    ?? NULL,
            $request->bTareaEsEvaluado      ?? NULL,
            $request->bTareaEsRestringida   ?? NULL,
            $request->bTareaEsGrupal        ?? NULL,
            $request->dtTareaInicio         ?? NULL,
            $request->dtTareaFin            ?? NULL,
            $request->cTareaComentarioDocente   ?? NULL,
            $request->iEstado                   ?? NULL,
            $request->iSesionId                 ?? NULL,
            $request->dtCreado                  ?? NULL,
            $request->dtActualizado             ?? NULL,

            //$request->iCredId

        ];
        //return $parametros;
        try {
            $data = DB::select('exec aula.Sp_AULA_CRUD_TAREAS
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            if ($data[0]->iTareaId > 0) {

                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }
}
