<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hashids\Hashids;

class NotificacionEstudianteController extends Controller
{   
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function mostrar_notificacion(Request $request){

        $request['iEstudianteId'] = is_null($request->iEstudianteId)
            ? null
            : (is_numeric($request->iEstudianteId)
                ? $request->iEstudianteId
                : ($this->hashids->decode($request->iEstudianteId)[0] ?? null));


        $parametros = [$request->iEstudianteId];
   
        try {
            $data = DB::select('exec aula.Sp_SEL_notificacion_estudiante ?', $parametros);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $estado = 500;
        }

        return new JsonResponse($response,$estado);
    }
}
