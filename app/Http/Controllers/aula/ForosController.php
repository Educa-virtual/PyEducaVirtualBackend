<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class ForosController extends Controller
{

    protected $hashids;

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

    public function obtenerForoxiForoId(Request $request)
    {
        if ($request->iForoId) {
            $iForoId = $this->hashids->decode($request->iForoId);
            $iForoId = count($iForoId) > 0 ? $iForoId[0] : $iForoId;
        }
        $parametros = [
            $iForoId,
        ];

        try {
            $data = DB::select('exec aula.SP_SEL_Foro
                ?', $parametros);

            $response = ['validated' => true, 'mensaje' => 'Se octuvo la información exitosamente.', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function actualizarForo(Request $request)
    {
        $parametros = [
            $request->iForoId,
            $request->iForoCatId,
            $request->iDocenteId,
            $request->cForoTitulo,
            $request->cForoDescripcion,
            $request->dtForoPublicacion,
            $request->dtForoInicio,
            $request->dtForoFin,
            $request->iEstado ?? 1
        ];

        try {
            $data = DB::update('exec aula.SP_UPD_foro
                ?,?,?,?,?,?,?,?,?', $parametros);

            $response = ['validated' => true, 'mensaje' => 'Se octuvo la información exitosamente.', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function eliminarxiForoId(Request $request)
    {   
        if (isset($request->iForoId)) {
            $request['iForoId'] = $this->decodeValue($request->iForoId);
        }
        $parametros = [
            $request->opcion            ??      NULL,
            $request->valorBusqueda     ??      NULL,
            $request->iForoId           ??      NULL
        ];

        try {
            $data = DB::select('exec aula.SP_DEL_foros
               ?,?,?', $parametros);

            if ($data[0]->iForoId > 0) {

                $response = ['validated' => true, 'mensaje' => 'Se eliminó la información exitosamente.'];
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
