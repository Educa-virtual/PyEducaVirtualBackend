<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;
use Illuminate\Support\Carbon;

class ProgramacionActividadesController extends Controller
{
    protected $hashids;
    protected $iProgActId;
    protected $iSilaboActAprendId;
    protected $iContenidoSemId;
    protected $iInstrumentoId;
    protected $iActTipoId;
    protected $iHorarioId;


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
        if ($request->iProgActId) {
            $iProgActId = $this->hashids->decode($request->iProgActId);
            $iProgActId = count($iProgActId) > 0 ? $iProgActId[0] : $iProgActId;
        }
        if ($request->iSilaboActAprendId) {
            $iSilaboActAprendId = $this->hashids->decode($request->iSilaboActAprendId);
            $iSilaboActAprendId = count($iSilaboActAprendId) > 0 ? $iSilaboActAprendId[0] : $iSilaboActAprendId;
        }
        if ($request->iContenidoSemId) {
            $iContenidoSemId = $this->hashids->decode($request->iContenidoSemId);
            $iContenidoSemId = count($iContenidoSemId) > 0 ? $iContenidoSemId[0] : $iContenidoSemId;
        }
        if ($request->iInstrumentoId) {
            $iInstrumentoId = $this->hashids->decode($request->iInstrumentoId);
            $iInstrumentoId = count($iInstrumentoId) > 0 ? $iInstrumentoId[0] : $iInstrumentoId;
        }
        if ($request->iActTipoId) {
            $iActTipoId = $this->hashids->decode($request->iActTipoId);
            $iActTipoId = count($iActTipoId) > 0 ? $iActTipoId[0] : $iActTipoId;
        }
        if ($request->iHorarioId) {
            $iHorarioId = $this->hashids->decode($request->iHorarioId);
            $iHorarioId = count($iHorarioId) > 0 ? $iHorarioId[0] : $iHorarioId;
        }


        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iProgActId                     ?? NULL,
            $iSilaboActAprendId             ?? NULL,
            $iContenidoSemId                ?? NULL,
            $iInstrumentoId                 ?? NULL,
            $iActTipoId                     ?? NULL,
            $request->dtProgActPublicacion  ?? NULL,
            $request->nProgActConceptual    ?? NULL,
            $request->nProgActProcedimiental    ?? NULL,
            $request->nProgActActitudinal       ?? NULL,
            $request->bProgActEsEvaluado        ?? NULL,
            $request->cProgActTituloLeccion     ?? NULL,
            $request->cProgActDescripcion       ?? NULL,
            $request->bProgActEsObligatorio     ?? NULL,
            $request->bProgActEsRestringido     ?? NULL,
            $request->dtProgActInicio           ?? NULL,
            $request->dtProgActFin              ?? NULL,
            $request->nProgActNota              ?? NULL,
            $request->cProgActComentarioDocente ?? NULL,
            $request->iEstado                   ?? NULL,
            $request->iSesionId                 ?? NULL,
            $request->dtCreado                  ?? NULL,
            $request->dtActualizado             ?? NULL,
            $iHorarioId                         ?? NULL,

            //$request->iCredId

        ];

        try {
            $data = DB::select('exec SP_crudProgramacionActividades
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            foreach ($data as $key => $value) {
                $value->iProgActId = $this->hashids->encode($value->iProgActId);
                $value->iSilaboActAprendId = $this->hashids->encode($value->iSilaboActAprendId);
                $value->iContenidoSemId = $this->hashids->encode($value->iContenidoSemId);
                $value->iInstrumentoId = $this->hashids->encode($value->iInstrumentoId);
                $value->iActTipoId = $this->hashids->encode($value->iActTipoId);
                $value->iHorarioId = $this->hashids->encode($value->iHorarioId);
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
        if ($request->iProgActId) {
            $iProgActId = $this->hashids->decode($request->iProgActId);
            $iProgActId = count($iProgActId) > 0 ? $iProgActId[0] : $iProgActId;
        }
        if ($request->iSilaboActAprendId) {
            $iSilaboActAprendId = $this->hashids->decode($request->iSilaboActAprendId);
            $iSilaboActAprendId = count($iSilaboActAprendId) > 0 ? $iSilaboActAprendId[0] : $iSilaboActAprendId;
        }
        if ($request->iContenidoSemId) {
            $iContenidoSemId = $this->hashids->decode($request->iContenidoSemId);
            $iContenidoSemId = count($iContenidoSemId) > 0 ? $iContenidoSemId[0] : $iContenidoSemId;
        }
        if ($request->iInstrumentoId) {
            $iInstrumentoId = $this->hashids->decode($request->iInstrumentoId);
            $iInstrumentoId = count($iInstrumentoId) > 0 ? $iInstrumentoId[0] : $iInstrumentoId;
        }
        if ($request->iActTipoId) {
            $iActTipoId = $this->hashids->decode($request->iActTipoId);
            $iActTipoId = count($iActTipoId) > 0 ? $iActTipoId[0] : $iActTipoId;
        }
        if ($request->iHorarioId) {
            $iHorarioId = $this->hashids->decode($request->iHorarioId);
            $iHorarioId = count($iHorarioId) > 0 ? $iHorarioId[0] : $iHorarioId;
        }


        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iProgActId                     ?? NULL,
            $iSilaboActAprendId             ?? NULL,
            $iContenidoSemId                ?? NULL,
            $iInstrumentoId                 ?? NULL,
            $request->iActTipoId                     ?? NULL,
            $request->dtProgActPublicacion  ?? NULL,
            $request->nProgActConceptual    ?? NULL,
            $request->nProgActProcedimiental    ?? NULL,
            $request->nProgActActitudinal       ?? NULL,
            $request->bProgActEsEvaluado        ?? NULL,
            $request->cProgActTituloLeccion     ?? NULL,
            $request->cProgActDescripcion       ?? NULL,
            $request->bProgActEsObligatorio     ?? NULL,
            $request->bProgActEsRestringido     ?? NULL,
            $request->dtProgActInicio           ?? NULL,
            $request->dtProgActFin              ?? NULL,
            $request->nProgActNota              ?? NULL,
            $request->cProgActComentarioDocente ?? NULL,
            $request->iEstado                   ?? NULL,
            $request->iSesionId                 ?? NULL,
            $request->dtCreado                  ?? NULL,
            $request->dtActualizado             ?? NULL,
            $iHorarioId                         ?? NULL,

            //$request->iCredId

        ];

        try {
            $data = DB::select('exec aula.SP_INS_aulaProgramacionActividades
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            switch ($request->opcion) {
                case 'GUARDARxProgActxiTarea':
                    if ($data[0]->iProgActId > 0) {
                        $request['iProgActId'] = $this->hashids->encode($data[0]->iProgActId);
                        $resp = new TareasController();
                    }
                    return $resp->store($request);
                    break;
                default:
                    if ($data[0]->iProgActId > 0) {

                        $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                        $codeResponse = 200;
                    } else {
                        $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                        $codeResponse = 500;
                    }
                    break;
            }
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }
}