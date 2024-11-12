<?php

namespace App\Http\Controllers\doc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class TiposCargaNoLectivasController extends Controller
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

        $request['iTipoCargaNoLectId'] = is_null($request->iTipoCargaNoLectId)
            ? null
            : (is_numeric($request->iTipoCargaNoLectId)
                ? $request->iTipoCargaNoLectId
                : ($this->hashids->decode($request->iTipoCargaNoLectId)[0] ?? null));

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iTipoCargaNoLectId            ?? NULL,
            $request->cTipoCargaNoLectNombre        ?? NULL,
            $request->cTipoCargaNoLectDescripcion   ?? NULL,

            $request->iCredId

        ];

        return $parametros;
    }

    public function list(Request $request)
    {   
        
        $resp = new TiposCargaNoLectivasController();
        $parametros = $resp->validate($request);

        try {
            $data = DB::select('exec doc.Sp_SEL_tiposCargaNoLectivas
                ?,?,?,?,?,?', $parametros);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }
}
