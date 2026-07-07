<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use App\Http\Controllers\eval\EvaluacionesController;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    private function decodeValue($value)
    {
        if (is_null($value)) {
            return null;
        }

        return is_numeric($value) ? $value : ($this->hashids->decode($value)[0] ?? null);
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

            $iProgActId ?? null,
            $iSilaboActAprendId ?? null,
            $iContenidoSemId ?? null,
            $iInstrumentoId ?? null,
            $iActTipoId ?? null,
            $request->dtProgActPublicacion ?? null,
            $request->nProgActConceptual ?? null,
            $request->nProgActProcedimiental ?? null,
            $request->nProgActActitudinal ?? null,
            $request->bProgActEsEvaluado ?? null,
            $request->cProgActTituloLeccion ?? null,
            $request->cProgActDescripcion ?? null,
            $request->bProgActEsObligatorio ?? null,
            $request->bProgActEsRestringido ?? null,
            $request->dtProgActInicio ?? null,
            $request->dtProgActFin ?? null,
            $request->nProgActNota ?? null,
            $request->cProgActComentarioDocente ?? null,
            $request->iEstado ?? null,
            $request->iSesionId ?? null,
            $request->dtCreado ?? null,
            $request->dtActualizado ?? null,
            $iHorarioId ?? null,

            // $request->iCredId

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
        $fieldsToDecode = [
            'valorBusqueda',

            'iProgActId',
            'iSilaboActAprendId',
            'iContenidoSemId',
            'iInstrumentoId',
            'iActTipoId',
            'iHorarioId',

        ];

        foreach ($fieldsToDecode as $field) {
            $request[$field] = $this->decodeValue($request->$field);
        }

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iProgActId ?? null,
            $request->iSilaboActAprendId ?? null,
            $request->iContenidoSemId ?? null,
            $request->iInstrumentoId ?? null,
            $request->iActTipoId ?? null,
            $request->dtProgActPublicacion ?? null,
            $request->nProgActConceptual ?? null,
            $request->nProgActProcedimiental ?? null,
            $request->nProgActActitudinal ?? null,
            $request->bProgActEsEvaluado ?? null,
            $request->cProgActTituloLeccion ?? null,
            $request->cProgActDescripcion ?? null,
            $request->bProgActEsObligatorio ?? null,
            $request->bProgActEsRestringido ?? null,
            $request->dtProgActInicio ?? null,
            $request->dtProgActFin ?? null,
            $request->nProgActNota ?? null,
            $request->cProgActComentarioDocente ?? null,
            $request->iEstado ?? null,
            $request->iSesionId ?? null,
            $request->dtCreado ?? null,
            $request->dtActualizado ?? null,
            $request->iHorarioId ?? null,

            $request->idDocCursoId ?? null,

            // $request->iCredId

        ];

        try {
            switch ($request->opcion) {
                case 'GUARDARxProgActxiTarea':
                    $data = DB::select('exec aula.SP_INS_aulaProgramacionActividades
                    ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iProgActId > 0) {
                        $request['iProgActId'] = $this->hashids->encode($data[0]->iProgActId);
                        $resp = new TareasController;
                    }

                    return $resp->store($request);
                    break;
                case 'GUARDARxProgActxiEvaluacionId':
                    $data = DB::select('exec aula.SP_INS_aulaProgramacionActividades
                    ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iProgActId > 0) {
                        $request['iProgActId'] = $this->hashids->encode($data[0]->iProgActId);
                        $resp = new EvaluacionesController;
                    }

                    return $resp->handleCrudOperation($request);
                    break;
                case 'ACTUALIZARxProgActxiEvaluacionId':
                    $data = DB::select('exec aula.SP_UPD_programacionActividades
                    ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iProgActId > 0) {
                        $request['iProgActId'] = $this->hashids->encode($data[0]->iProgActId);
                        $resp = new EvaluacionesController;
                    }

                    return $resp->handleCrudOperation($request);
                    break;
                case 'GUARDARxProgActxiCuestionarioId':
                    $data = DB::select('exec aula.SP_INS_aulaProgramacionActividades
                    ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iProgActId > 0) {
                        $request['iProgActId'] = $this->hashids->encode($data[0]->iProgActId);
                        $resp = new CuestionariosController;
                    }

                    return $resp->guardarCuestionario($request);
                    break;
                case 'GUARDARxProgActxiRVirtualId':
                    $data = DB::select('exec aula.SP_INS_aulaProgramacionActividades
                    ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iProgActId > 0) {
                        $request['iProgActId'] = $this->hashids->encode($data[0]->iProgActId);
                        $resp = new ReunionVirtualesController;
                    }

                    return $resp->guardarReunionVirtuales($request);
                    break;
                default:
                    $data = DB::select('exec aula.SP_INS_aulaProgramacionActividades
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
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
