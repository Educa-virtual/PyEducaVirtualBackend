<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class ContenidoSemanasController extends Controller
{
    protected $hashids;
    protected $iContenidoSemId;
    protected $iIndActId;

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
        if ($request->iContenidoSemId) {
            $iContenidoSemId = $this->hashids->decode($request->iContenidoSemId);
            $iContenidoSemId = count($iContenidoSemId) > 0 ? $iContenidoSemId[0] : $iContenidoSemId;
        }
        if ($request->iIndActId) {
            $iIndActId = $this->hashids->decode($request->iIndActId);
            $iIndActId = count($iIndActId) > 0 ? $iIndActId[0] : $iIndActId;
        }

        switch ($request->opcion) {
            case 'CONSULTARxiSilaboId':
                $request['valorBusqueda'] = $this->hashids->decode($request->valorBusqueda);
                $request['valorBusqueda'] = count($request->valorBusqueda) > 0 ? $request->valorBusqueda[0] : $request->valorBusqueda;
                break;
        }
/*
        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iContenidoSemId                    ?? NULL,
            $iIndActId                          ?? NULL,
            $request->cContenidoSemTitulo       ?? NULL,
            $request->cContenidoSemNumero       ?? NULL,
            $request->cContenidoSemDescripcion  ?? NULL,

            $request->iCredId

        ];*/
//-----Linea de código Optimizada---------------
        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',
            $iContenidoSemId ?? null,
            $iIndActId ?? null,
            ...array_map(fn($key) => $request->$key ?? null, [
                'cContenidoSemTitulo',
                'cContenidoSemNumero',
                'cContenidoSemDescripcion',
                'iCredId'
            ])
        ];
//----------------------------------------------
        try {
            $data = DB::select('exec acad.Sp_SEL_contenidoSemanas
                ?,?,?,?,?,?,?,?', $parametros);

            foreach ($data as $key => $value) {
                $value->iContenidoSemId = $this->hashids->encode($value->iContenidoSemId);
                $value->iIndActId = $this->hashids->encode($value->iIndActId);
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
        if ($request->iContenidoSemId) {
            $iContenidoSemId = $this->hashids->decode($request->iContenidoSemId);
            $iContenidoSemId = count($iContenidoSemId) > 0 ? $iContenidoSemId[0] : $iContenidoSemId;
        }
        if ($request->iIndActId) {
            $iIndActId = $this->hashids->decode($request->iIndActId);
            $iIndActId = count($iIndActId) > 0 ? $iIndActId[0] : $iIndActId;
        }

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iContenidoSemId                    ?? NULL,
            $iIndActId                          ?? NULL,
            $request->cContenidoSemTitulo       ?? NULL,
            $request->cContenidoSemNumero       ?? NULL,
            $request->cContenidoSemDescripcion  ?? NULL,

            $request->iCredId

        ];

        try {
            switch ($request->opcion) {
                case 'GUARDARxiIndActId':
                    $data = DB::select('exec acad.Sp_INS_contenidoSemanas
                    ?,?,?,?,?,?,?,?', $parametros);
                    break;
                case 'ACTUALIZARxiContenidoSemId':
                    $data = DB::select('exec acad.Sp_UPD_contenidoSemanas
                    ?,?,?,?,?,?,?,?', $parametros);
                    break;
                case 'ELIMINARxiContenidoSemId':
                    $data = DB::select('exec acad.Sp_DEL_contenidoSemanas
                    ?,?,?,?,?,?,?,?', $parametros);
                    break;
            }
            if ($data[0]->iContenidoSemId > 0) {
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
