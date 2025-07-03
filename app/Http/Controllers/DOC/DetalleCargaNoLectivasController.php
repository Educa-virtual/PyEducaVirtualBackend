<?php

namespace App\Http\Controllers\doc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class DetalleCargaNoLectivasController extends Controller
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function validate(Request $request)
    {
        $request->validate(
            [
                'opcion' => 'required',
            ],
            [
                'opcion.required' => 'Hubo un problema al obtener la acción',
            ]
        );
        $request['valorBusqueda'] = is_null($request->valorBusqueda)
            ? null
            : (is_numeric($request->valorBusqueda)
                ? $request->valorBusqueda
                : ($this->hashids->decode($request->valorBusqueda)[0] ?? null));

        $request['iDetCargaNoLectId'] = is_null($request->iDetCargaNoLectId)
            ? null
            : (is_numeric($request->iDetCargaNoLectId)
                ? $request->iDetCargaNoLectId
                : ($this->hashids->decode($request->iDetCargaNoLectId)[0] ?? null));

        $request['iCargaNoLectivaId'] = is_null($request->iCargaNoLectivaId)
            ? null
            : (is_numeric($request->iCargaNoLectivaId)
                ? $request->iCargaNoLectivaId
                : ($this->hashids->decode($request->iCargaNoLectivaId)[0] ?? null));

        $request['iTipoCargaNoLectId'] = is_null($request->iTipoCargaNoLectId)
            ? null
            : (is_numeric($request->iTipoCargaNoLectId)
                ? $request->iTipoCargaNoLectId
                : ($this->hashids->decode($request->iTipoCargaNoLectId)[0] ?? null));

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iDetCargaNoLectId             ?? NULL,
            $request->iCargaNoLectivaId             ?? NULL,
            $request->iTipoCargaNoLectId            ?? NULL,
            $request->nDetCargaNoLectHoras          ?? NULL,
            $request->cDetCargaNoLectEvidencias     ?? NULL,

            $request->iCredId

        ];

        return $parametros;
    }

    public function list(Request $request)
    {
        $resp = new DetalleCargaNoLectivasController();
        $parametros = $resp->validate($request);

        try {
            $data = DB::select('exec doc.Sp_SEL_detalleCargaNoLectivas
                ?,?,?,?,?,?,?,?', $parametros);

            $response = ['validated' => true, 'mensaje' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function store(Request $request)
    {
        $resp = new DetalleCargaNoLectivasController();
        $parametros = $resp->validate($request);

        try {
            $data = DB::select('exec doc.Sp_INS_detalleCargaNoLectivas
                ?,?,?,?,?,?,?,?', $parametros);
            
            if ($data[0]->iDetCargaNoLectId > 0) {

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

    public function update(Request $request)
    {
        $resp = new DetalleCargaNoLectivasController();
        $parametros = $resp->validate($request);

        try {
            $data = DB::select('exec doc.Sp_UPD_detalleCargaNoLectivas
                ?,?,?,?,?,?,?,?', $parametros);

            if ($data[0]->iDetCargaNoLectId > 0) {

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

    public function delete(Request $request)
    {   
        
        $resp = new DetalleCargaNoLectivasController();
        $parametros = $resp->validate($request);

        try {
            $data = DB::select('exec doc.Sp_DEL_detalleCargaNoLectivas
                ?,?,?,?,?,?,?,?', $parametros);
            
            
            if ($data[0]->iDetCargaNoLectId > 0) {

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
