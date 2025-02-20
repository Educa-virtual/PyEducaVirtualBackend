<?php
namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class DetalleEvaluacionesController extends Controller
{
    protected $hashids;
    protected $iDetEvaId;
    protected $iSilaboId;

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
        if ($request->iDetEvaId) {
            $iDetEvaId = $this->hashids->decode($request->iDetEvaId);
            $iDetEvaId = count($iDetEvaId) > 0 ? $iDetEvaId[0] : $iDetEvaId;
        }
        if ($request->iSilaboId) {
            $iSilaboId = $this->hashids->decode($request->iSilaboId);
            $iSilaboId = count($iSilaboId) > 0 ? $iSilaboId[0] : $iSilaboId;
        }
/*Linea de codigo antigua---------------------------------
        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iDetEvaId                              ?? NULL,
            $iSilaboId                              ?? NULL,
            $request->cDetEvalDetalles              ?? NULL,

            $request->iCredId
        ];*/
// Linea de codigo Optimizada----------------------
        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',
            $iDetEvaId ?? null,
            $iSilaboId ?? null,
            ...array_map(fn($key) => $request->$key ?? null, [
                'cDetEvalDetalles',
                'iCredId'
            ])
        ];

//Fin de linea optimizada--------------------------
        try {
            $data = DB::select('exec acad.Sp_ACAD_CRUD_DETALLE_EVALUACIONES
                ?,?,?,?,?,?', $parametros);

            foreach ($data as $key => $value) {
                $value->iDetEvaId = $this->hashids->encode($value->iDetEvaId);
                $value->iSilaboId = $this->hashids->encode($value->iSilaboId);
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
        if ($request->iDetEvaId) {
            $iDetEvaId = $this->hashids->decode($request->iDetEvaId);
            $iDetEvaId = count($iDetEvaId) > 0 ? $iDetEvaId[0] : $iDetEvaId;
        }
        if ($request->iSilaboId) {
            $iSilaboId = $this->hashids->decode($request->iSilaboId);
            $iSilaboId = count($iSilaboId) > 0 ? $iSilaboId[0] : $iSilaboId;
        }
/*Linea de codigo antigua--------------------------------------
        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iDetEvaId                              ?? NULL,
            $iSilaboId                              ?? NULL,
            $request->cDetEvalDetalles              ?? NULL,

            $request->iCredId
        ];*/
//Linea de Codigo nueva Optimizada------------------------------
        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',
            $iDetEvaId ?? null,
            $iSilaboId ?? null,
            ...array_map(fn($key) => $request->$key ?? null, [
                'cDetEvalDetalles',
                'iCredId'
            ])
        ];

//Fin del codigo optimizado-------------------------------------
        try {
            $data = DB::select('exec acad.Sp_ACAD_CRUD_DETALLE_EVALUACIONES
                ?,?,?,?,?,?', $parametros);

            if ($data[0]->iDetEvaId > 0) {

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
