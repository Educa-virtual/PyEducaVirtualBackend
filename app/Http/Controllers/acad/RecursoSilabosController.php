<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class RecursoSilabosController extends Controller
{
    protected $hashids;
    protected $iRecSilaboId;
    protected $iSilaboId;
    protected $iRecDidacticoId;


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
        if ($request->iRecSilaboId) {
            $iRecSilaboId = $this->hashids->decode($request->iRecSilaboId);
            $iRecSilaboId = count($iRecSilaboId) > 0 ? $iRecSilaboId[0] : $iRecSilaboId;
        }
        if ($request->iSilaboId) {
            $iSilaboId = $this->hashids->decode($request->iSilaboId);
            $iSilaboId = count($iSilaboId) > 0 ? $iSilaboId[0] : $iSilaboId;
        }
        if ($request->iRecDidacticoId) {
            $iRecDidacticoId = $this->hashids->decode($request->iRecDidacticoId);
            $iRecDidacticoId = count($iRecDidacticoId) > 0 ? $iRecDidacticoId[0] : $iRecDidacticoId;
        }


        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iRecSilaboId                           ?? NULL,
            $iSilaboId                              ?? NULL,
            $iRecDidacticoId                        ?? NULL,
            $request->cRecSilaboDescripcion         ?? NULL,

            $request->iCredId

        ];

        try {
            $data = DB::select('exec acad.Sp_ACAD_CRUD_RECURSO_SILABOS
                ?,?,?,?,?,?,?', $parametros);

            foreach ($data as $key => $value) {
                $value->iRecSilaboId = $this->hashids->encode($value->iRecSilaboId);
                $value->iSilaboId = $this->hashids->encode($value->iSilaboId);
                $value->iRecDidacticoId = $this->hashids->encode($value->iRecDidacticoId);
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
        if ($request->iRecSilaboId) {
            $iRecSilaboId = $this->hashids->decode($request->iRecSilaboId);
            $iRecSilaboId = count($iRecSilaboId) > 0 ? $iRecSilaboId[0] : $iRecSilaboId;
        }
        if ($request->iSilaboId) {
            $iSilaboId = $this->hashids->decode($request->iSilaboId);
            $iSilaboId = count($iSilaboId) > 0 ? $iSilaboId[0] : $iSilaboId;
        }
        if ($request->iRecDidacticoId) {
            $iRecDidacticoId = $this->hashids->decode($request->iRecDidacticoId);
            $iRecDidacticoId = count($iRecDidacticoId) > 0 ? $iRecDidacticoId[0] : $iRecDidacticoId;
        }


        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iRecSilaboId                           ?? NULL,
            $iSilaboId                              ?? NULL,
            $iRecDidacticoId                        ?? NULL,
            $request->cRecSilaboDescripcion         ?? NULL,

            $request->iCredId

        ];

        try {
            $data = DB::select('exec acad.Sp_ACAD_CRUD_RECURSO_SILABOS
                ?,?,?,?,?,?,?', $parametros);

            if ($data[0]->iRecSilaboId > 0) {

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
