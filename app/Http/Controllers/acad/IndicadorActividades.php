<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class IndicadorActividades extends Controller
{
    protected $hashids;
    protected $iIndActId;
    protected $iSilaboActAprendId;
    protected $iTipoIndLogId;

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
        if ($request->iIndActId) {
            $iIndActId = $this->hashids->decode($request->iIndActId);
            $iIndActId = count($iIndActId) > 0 ? $iIndActId[0] : $iIndActId;
        }
        if ($request->iSilaboActAprendId) {
            $iSilaboActAprendId = $this->hashids->decode($request->iSilaboActAprendId);
            $iSilaboActAprendId = count($iSilaboActAprendId) > 0 ? $iSilaboActAprendId[0] : $iSilaboActAprendId;
        }

        if ($request->iTipoIndLogId) {
            $iTipoIndLogId = $this->hashids->decode($request->iTipoIndLogId);
            $iTipoIndLogId = count($iTipoIndLogId) > 0 ? $iTipoIndLogId[0] : $iTipoIndLogId;
        }

        switch ($request->opcion) {
            case 'CONSULTARxiSilaboId':
                $request['valorBusqueda'] = $this->hashids->decode($request->valorBusqueda);
                $request['valorBusqueda'] = count($request->valorBusqueda) > 0 ? $request->valorBusqueda[0] : $request->valorBusqueda;
                break;
        }

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iIndActId                        ?? NULL,
            $request->cIndActNumero           ?? NULL,
            $request->iIndActSemanaEval       ?? NULL,
            $request->cIndActDescripcion      ?? NULL,
            $request->bIndActEsEvaluado       ?? NULL,
            $iSilaboActAprendId               ?? NULL,
            $request->cIndActProcedimientos   ?? NULL,
            $request->cIndActActitudes        ?? NULL,
            $request->cIndActConceptual       ?? NULL,
            $request->IndActHoras             ?? NULL,
            $iTipoIndLogId                    ?? NULL,
            $request->cIndActNombre           ?? NULL,

            $request->iCredId

        ];

        try {
            $data = DB::select('exec acad.Sp_ACAD_CRUD_INDICADOR_ACTIVIDADES
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            switch ($request->opcion) {
                default:
                    foreach ($data as $key => $value) {
                        $value->iIndActId = $this->hashids->encode($value->iIndActId);
                        $value->iSilaboActAprendId = $this->hashids->encode($value->iSilaboActAprendId);
                        $value->iTipoIndLogId = $this->hashids->encode($value->iTipoIndLogId);
                    }
                    break;
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
        if ($request->iIndActId) {
            $iIndActId = $this->hashids->decode($request->iIndActId);
            $iIndActId = count($iIndActId) > 0 ? $iIndActId[0] : $iIndActId;
        }
        if ($request->iSilaboActAprendId) {
            $iSilaboActAprendId = $this->hashids->decode($request->iSilaboActAprendId);
            $iSilaboActAprendId = count($iSilaboActAprendId) > 0 ? $iSilaboActAprendId[0] : $iSilaboActAprendId;
        }

        if ($request->iTipoIndLogId) {
            $iTipoIndLogId = $this->hashids->decode($request->iTipoIndLogId);
            $iTipoIndLogId = count($iTipoIndLogId) > 0 ? $iTipoIndLogId[0] : $iTipoIndLogId;
        }

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iIndActId                        ?? NULL,
            $request->cIndActNumero           ?? NULL,
            $request->iIndActSemanaEval       ?? NULL,
            $request->cIndActDescripcion      ?? NULL,
            $request->bIndActEsEvaluado       ?? NULL,
            $iSilaboActAprendId               ?? NULL,
            $request->cIndActProcedimientos   ?? NULL,
            $request->cIndActActitudes        ?? NULL,
            $request->cIndActConceptual       ?? NULL,
            $request->IndActHoras             ?? NULL,
            $iTipoIndLogId           ?? NULL,
            $request->cIndActNombre           ?? NULL,

            $request->iCredId

        ];

        try {
            $data = DB::select('exec acad.Sp_ACAD_CRUD_INDICADOR_ACTIVIDADES
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            if ($data[0]->iIndActId > 0) {

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
