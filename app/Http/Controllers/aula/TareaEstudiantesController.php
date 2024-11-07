<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class TareaEstudiantesController extends Controller
{
    protected $hashids;

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
        if ($request->iTareaEstudianteId) {
            $iTareaEstudianteId = $this->hashids->decode($request->iTareaEstudianteId);
            $iTareaEstudianteId = count($iTareaEstudianteId) > 0 ? $iTareaEstudianteId[0] : $iTareaEstudianteId;
        }
        if ($request->iTareaId) {
            $iTareaId = $this->hashids->decode($request->iTareaId);
            $iTareaId = count($iTareaId) > 0 ? $iTareaId[0] : $iTareaId;
        }
        if ($request->iEstudianteId) {
            $iEstudianteId = $this->hashids->decode($request->iEstudianteId);
            $iEstudianteId = count($iEstudianteId) > 0 ? $iEstudianteId[0] : $iEstudianteId;
        }
        if ($request->iEscalaCalifId) {
            $iEscalaCalifId = $this->hashids->decode($request->iEscalaCalifId);
            $iEscalaCalifId = count($iEscalaCalifId) > 0 ? $iEscalaCalifId[0] : $iEscalaCalifId;
        }
        if ($request->iTareaCabGrupoId) {
            $iTareaCabGrupoId = $this->hashids->decode($request->iTareaCabGrupoId);
            $iTareaCabGrupoId = count($iTareaCabGrupoId) > 0 ? $iTareaCabGrupoId[0] : $iTareaCabGrupoId;
        }

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iTareaEstudianteId                    ??      NULL,
            $iTareaId                              ??      NULL,
            $iEstudianteId                         ??      NULL,
            $iEscalaCalifId                        ??      NULL,
            $request->nTareaEstudianteNota                  ??      NULL,
            $request->cTareaEstudianteComentarioDocente     ??      NULL,
            $request->cTareaEstudianteUrlEstudiante         ??      NULL,
            $request->iEstado                               ??      NULL,
            $request->iSesionId                             ??      NULL,
            $request->dtCreado                              ??      NULL,
            $request->dtActualizado                         ??      NULL,
            $iTareaCabGrupoId                      ??      NULL

            //$request->iCredId

        ];

        try {
            $data = DB::select('exec aula.SP_aulaCrudTareaEstudiantes
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            foreach ($data as $key => $value) {
                $value->iTareaEstudianteId = $this->hashids->encode($value->iTareaEstudianteId);
                $value->iTareaId = $this->hashids->encode($value->iTareaId);
                $value->iEstudianteId = $this->hashids->encode($value->iEstudianteId);
                $value->iEscalaCalifId = $this->hashids->encode($value->iEscalaCalifId);
                $value->iTareaCabGrupoId = $this->hashids->encode($value->iTareaCabGrupoId);
            }

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function guardarCalificacionDocente(Request $request)
    {

        $request->validate(
            [
                'opcion' => 'required',
            ],
            [
                'opcion.required' => 'Hubo un problema al obtener la acción',
            ]
        );
        if ($request->iTareaEstudianteId) {
            $iTareaEstudianteId = $this->hashids->decode($request->iTareaEstudianteId);
            $iTareaEstudianteId = count($iTareaEstudianteId) > 0 ? $iTareaEstudianteId[0] : $iTareaEstudianteId;
        }
        if ($request->iEscalaCalifId) {
            $iEscalaCalifId = $this->hashids->decode($request->iEscalaCalifId);
            $iEscalaCalifId = count($iEscalaCalifId) > 0 ? $iEscalaCalifId[0] : $iEscalaCalifId;
        }

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iTareaEstudianteId                    ??      NULL,
            $request->iTareaId                              ??      NULL,
            $request->iEstudianteId                         ??      NULL,
            $iEscalaCalifId                        ??      NULL,
            $request->nTareaEstudianteNota                  ??      NULL,
            $request->cTareaEstudianteComentarioDocente     ??      NULL,
            $request->cTareaEstudianteUrlEstudiante         ??      NULL,
            $request->iEstado                               ??      NULL,
            $request->iSesionId                             ??      NULL,
            $request->dtCreado                              ??      NULL,
            $request->dtActualizado                         ??      NULL,
            $request->iTareaCabGrupoId                      ??      NULL

        ];

        try {
            $data = DB::select('exec aula.SP_aulaCrudTareaEstudiantes
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            if ($data[0]->iTareaEstudianteId > 0) {

                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }
    public function entregarEstudianteTarea(Request $request)
    {
        if ($request->iTareaId) {
            $iTareaId = $this->hashids->decode($request->iTareaId);
            $iTareaId = count($iTareaId) > 0 ? $iTareaId[0] : $iTareaId;
        }
        $parametros = [
            $iTareaId,
            $request->iEstudianteId,
            $request->cTareaEstudianteUrlEstudiante,
        ];

        try {
            $data = DB::select('exec aula.SP_UPD_tareaEstudiantesxEntregarEstudianteTarea
                ?,?,?', $parametros);

            if ($data[0]->iTareaEstudianteId > 0) {

                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }
}