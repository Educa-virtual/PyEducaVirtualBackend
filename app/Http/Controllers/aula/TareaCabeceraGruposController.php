<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class TareaCabeceraGruposController extends Controller
{
    protected $hashids;
    protected $iTareaCabGrupoId;
    protected $iTareaId;

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
        // if ($request->iTareaCabGrupoId) {
        //     $iTareaCabGrupoId = $this->hashids->decode($request->iTareaCabGrupoId);
        //     $iTareaCabGrupoId = count($iTareaCabGrupoId) > 0 ? $iTareaCabGrupoId[0] : $iTareaCabGrupoId;
        // }
        if ($request->iTareaId) {
            $iTareaId = $this->hashids->decode($request->iTareaId);
            $iTareaId = count($iTareaId) > 0 ? $iTareaId[0] : $iTareaId;
        }

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',
            $iTareaCabGrupoId                       ?? NULL,
            $iTareaId                               ?? NULL,
            $request->cTareaGrupoNombre             ?? NULL,
            $request->nTareaGrupoNota               ?? NULL,
            $request->cTareaGrupoComentarioDocente  ?? NULL,
            $request->cTareaGrupoUrl                ?? NULL,
            $request->iEstado                       ?? NULL,
            $request->iSesionId                     ?? NULL,
            $request->dtCreado                      ?? NULL,
            $request->dtActualizado                 ?? NULL


        ];

        try {
            $data = DB::select('exec aula.SP_aulaCrudTareaCabeceraGrupos
                ?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            foreach ($data as $key => $value) {
                $value->iEscalaCalifId = $this->hashids->encode($value->iEscalaCalifId);
                $value->iTareaId = $this->hashids->encode($value->iTareaId);
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

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',
            $request->iTareaCabGrupoId                       ?? NULL,
            $iTareaId                               ?? NULL,
            $request->cTareaGrupoNombre,
            $request->nTareaGrupoNota               ?? NULL,
            $request->cTareaGrupoComentarioDocente  ?? NULL,
            $request->cTareaGrupoUrl                ?? NULL,
            $request->iEstado                       ?? NULL,
            $request->iSesionId                     ?? NULL,
            $request->dtCreado                      ?? NULL,
            $request->dtActualizado                 ?? NULL


        ];

        try {
            $data = DB::select('exec aula.SP_aulaCrudTareaCabeceraGrupos
                ?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            if ($data[0]->iTareaCabGrupoId > 0) {

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

    public function eliminarTareaCabeceraGrupos(Request $request){
        
        $parametros = [
            $request->iTareaCabGrupoId,
        ];

        try {
            $data = DB::select('exec aula.SP_DEL_tareaCabeceraGruposxiTareaCabGrupoId
                ?', $parametros);

            if ($data[0]->iTareaCabGrupoId > 0) {

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

    public function guardarCalificacionTareaCabeceraGruposDocente(Request $request){

        if ($request->iEscalaCalifId) {
            $iEscalaCalifId = $this->hashids->decode($request->iEscalaCalifId);
            $iEscalaCalifId = count($iEscalaCalifId) > 0 ? $iEscalaCalifId[0] : $iEscalaCalifId;
        }

        $parametros = [
            $request->iTareaCabGrupoId,
            $iEscalaCalifId,
            $request->cTareaGrupoComentarioDocente,
        ];

        try {
            $data = DB::select('exec aula.SP_UPD_tareaCabeceraGruposCalificarDocente
                ?,?,?', $parametros);

            if ($data[0]->iTareaCabGrupoId > 0) {

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
