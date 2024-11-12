<?php

namespace App\Http\Controllers\doc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class CargaNoLectivasController extends Controller
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

        $request['iCargaNoLectivaId'] = is_null($request->iCargaNoLectivaId)
            ? null
            : (is_numeric($request->iCargaNoLectivaId)
                ? $request->iCargaNoLectivaId
                : ($this->hashids->decode($request->iCargaNoLectivaId)[0] ?? null));

        $request['iSemAcadId'] = is_null($request->iSemAcadId)
            ? null
            : (is_numeric($request->iSemAcadId)
                ? $request->iSemAcadId
                : ($this->hashids->decode($request->iSemAcadId)[0] ?? null));

        $request['iYAcadId'] = is_null($request->iYAcadId)
            ? null
            : (is_numeric($request->iYAcadId)
                ? $request->iYAcadId
                : ($this->hashids->decode($request->iYAcadId)[0] ?? null));

        $request['iDocenteId'] = is_null($request->iDocenteId)
            ? null
            : (is_numeric($request->iDocenteId)
                ? $request->iDocenteId
                : ($this->hashids->decode($request->iDocenteId)[0] ?? null));

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iCargaNoLectivaId     ?? NULL,
            $request->iSemAcadId            ?? NULL,
            $request->iYAcadId              ?? NULL,
            $request->iDocenteId            ?? NULL,
            $request->iEstado               ?? NULL,
            $request->iSesionId             ?? NULL,
            $request->dtCreado              ?? NULL,
            $request->dtActualizado         ?? NULL,

            $request->iCredId

        ];

        return $parametros;
    }

    public function list(Request $request)
    {
        $resp = new CargaNoLectivasController();
        $parametros = $resp->validate($request);

        try {
            $data = DB::select('exec doc.Sp_SEL_cargaNoLectivas
                ?,?,?,?,?,?,?,?,?,?,?', $parametros);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function store(Request $request)
    {
        $resp = new CargaNoLectivasController();
        $parametros = $resp->validate($request);

        try {
            $data = DB::select('exec doc.Sp_INS_cargaNoLectivas
                ?,?,?,?,?,?,?,?,?,?,?', $parametros);

            switch ($request->opcion) {
                case 'GUARDARxDetalleCargaNoLectiva':
                    if ($data[0]->iCargaNoLectivaId > 0) {
                        $request['iCargaNoLectivaId'] = $this->hashids->encode($data[0]->iCargaNoLectivaId);
                        $resp = new DetalleCargaNoLectivasController();
                    }
                    return $resp->store($request);
                    break;
                default:
                    if ($data[0]->iCargaNoLectivaId > 0) {

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

    public function update(Request $request)
    {
        $resp = new CargaNoLectivasController();
        $parametros = $resp->validate($request);

        try {
            $data = DB::select('exec doc.Sp_UPD_cargaNoLectivas
                ?,?,?,?,?,?,?,?,?,?,?', $parametros);

            if ($data[0]->iCargaNoLectivaId > 0) {

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
        $resp = new CargaNoLectivasController();
        $parametros = $resp->validate($request);

        try {
            $data = DB::select('exec doc.Sp_DEL_cargaNoLectivas
                ?,?,?,?,?,?,?,?,?,?,?', $parametros);

            if ($data[0]->iCargaNoLectivaId > 0) {

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
