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

    public function actualizarForo(Request $request){
        $parametros = [
             $request->iForoId
            ,$request->iForoCatId
            ,$request->iDocenteId
            ,$request->cForoTitulo
            ,$request->cForoDescripcion
            ,$request->dtForoPublicacion
            ,$request->dtForoInicio
            ,$request->dtForoFin 
            ,$request->iEstado ?? 1
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
}
