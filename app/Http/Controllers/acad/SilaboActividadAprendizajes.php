<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class SilaboActividadAprendizajes extends Controller
{
    protected $hashids;
    protected $iSilaboActAprendId;
    protected $iSilaboId;
    protected $iIndLogorCapId;


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
        if ($request->iSilaboActAprendId) {
            $iSilaboActAprendId = $this->hashids->decode($request->iSilaboActAprendId);
            $iSilaboActAprendId = count($iSilaboActAprendId) > 0 ? $iSilaboActAprendId[0] : $iSilaboActAprendId;
        }
        if ($request->iSilaboId) {
            $iSilaboId = $this->hashids->decode($request->iSilaboId);
            $iSilaboId = count($iSilaboId) > 0 ? $iSilaboId[0] : $iSilaboId;
        }
        if ($request->iIndLogorCapId) {
            $iIndLogorCapId = $this->hashids->decode($request->iIndLogorCapId);
            $iIndLogorCapId = count($iIndLogorCapId) > 0 ? $iIndLogorCapId[0] : $iIndLogorCapId;
        }


        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iSilaboActAprendId            ?? NULL,
            $iSilaboId                     ?? NULL,
            $iIndLogorCapId                ?? NULL,
            $request->cSilaboActAprendNumero        ?? NULL,
            $request->cSilaboActAprendNombre        ?? NULL,
            $request->cSilaboActAprendElementos     ?? NULL,
            $request->dtSilaboActAprend             ?? NULL,
            $request->cSilaboActIndLogro            ?? NULL,
            $request->iSilaboActAprendSemanaEval    ?? NULL,
            $request->iSilaboActHoras               ?? NULL,

            $request->iCredId

        ];

        try {
            $data = DB::select('exec acad.Sp_ACAD_CRUD_SILABO_ACTIVIDAD_APRENDIZAJES
                ?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            foreach ($data as $key => $value) {
                $value->iSilaboActAprendId = $this->hashids->encode($value->iSilaboActAprendId);
                $value->iSilaboId = $this->hashids->encode($value->iSilaboId);
                $value->iIndLogorCapId = $this->hashids->encode($value->iIndLogorCapId);
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
        if ($request->iSilaboActAprendId) {
            $iSilaboActAprendId = $this->hashids->decode($request->iSilaboActAprendId);
            $iSilaboActAprendId = count($iSilaboActAprendId) > 0 ? $iSilaboActAprendId[0] : $iSilaboActAprendId;
        }
        if ($request->iSilaboId) {
            $iSilaboId = $this->hashids->decode($request->iSilaboId);
            $iSilaboId = count($iSilaboId) > 0 ? $iSilaboId[0] : $iSilaboId;
        }
        if ($request->iIndLogorCapId) {
            $iIndLogorCapId = $this->hashids->decode($request->iIndLogorCapId);
            $iIndLogorCapId = count($iIndLogorCapId) > 0 ? $iIndLogorCapId[0] : $iIndLogorCapId;
        }


        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iSilaboActAprendId            ?? NULL,
            $iSilaboId                     ?? NULL,
            $iIndLogorCapId                ?? NULL,
            $request->cSilaboActAprendNumero        ?? NULL,
            $request->cSilaboActAprendNombre        ?? NULL,
            $request->cSilaboActAprendElementos     ?? NULL,
            $request->dtSilaboActAprend             ?? NULL,
            $request->cSilaboActIndLogro            ?? NULL,
            $request->iSilaboActAprendSemanaEval    ?? NULL,
            $request->iSilaboActHoras               ?? NULL,

            $request->iCredId

        ];

        try {
            $data = DB::select('exec acad.Sp_ACAD_CRUD_SILABO_ACTIVIDAD_APRENDIZAJES
                ?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            if ($data[0]->iSilaboActAprendId > 0) {

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
