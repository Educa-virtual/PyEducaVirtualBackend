<?php

namespace App\Http\Controllers\DOC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class MaterialEducativosController extends Controller
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

        $request['iMatEducativoId'] = is_null($request->iMatEducativoId)
            ? null
            : (is_numeric($request->iMatEducativoId)
                ? $request->iMatEducativoId
                : ($this->hashids->decode($request->iMatEducativoId)[0] ?? null));

        $request['iDocenteId'] = is_null($request->iDocenteId)
            ? null
            : (is_numeric($request->iDocenteId)
                ? $request->iDocenteId
                : ($this->hashids->decode($request->iDocenteId)[0] ?? null));

        $request['iCursosNivelGradId'] = is_null($request->iCursosNivelGradId)
            ? null
            : (is_numeric($request->iCursosNivelGradId)
                ? $request->iCursosNivelGradId
                : ($this->hashids->decode($request->iCursosNivelGradId)[0] ?? null));

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iMatEducativoId              ?? NULL,
            $request->iDocenteId                   ?? NULL,
            $request->cMatEducativoTitulo          ?? NULL,
            $request->cMatEducativoDescripcion     ?? NULL,
            $request->dtMatEducativo               ?? NULL,
            $request->iEstado                      ?? NULL,
            $request->iSesionId                    ?? NULL,
            $request->dtCreado                     ?? NULL,
            $request->dtActualizado                ?? NULL,
            $request->iCursosNivelGradId           ?? NULL,
            $request->cMatEducativoUrl             ?? NULL,

            $request->iCredId

        ];

        return $parametros;
    }

    public function list(Request $request)
    {
        $resp = new MaterialEducativosController();
        $parametros = $resp->validate($request);

        try {
            $data = DB::select('exec doc.Sp_SEL_materialEducativoDocentes
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

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
        $resp = new MaterialEducativosController();
        $parametros = $resp->validate($request);

        try {
            $data = DB::select('exec doc.Sp_INS_materialEducativoDocentes
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            if ($data[0]->iMatEducativoId > 0) {

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
        $resp = new MaterialEducativosController();
        $parametros = $resp->validate($request);

        try {
            $data = DB::select('exec doc.Sp_UPD_materialEducativoDocentes
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                
            if ($data[0]->iMatEducativoId > 0) {
                $response = ['validated' => true, 'mensaje' => 'Se actualizó la información exitosamente.'];
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
        $resp = new MaterialEducativosController();
        $parametros = $resp->validate($request);

        try {
            $data = DB::select('exec doc.Sp_DEL_materialEducativoDocentes
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                
            if ($data[0]->iMatEducativoId > 0) {
                $response = ['validated' => true, 'mensaje' => 'Se eliminó la información exitosamente.'];
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
