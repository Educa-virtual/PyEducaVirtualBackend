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

    public function mostrar_notificacion(Request $request)
    {

        $request['iEstudianteId'] = is_null($request->iEstudianteId)
            ? null
            : (is_numeric($request->iEstudianteId)
                ? $request->iEstudianteId
                : ($this->hashids->decode($request->iEstudianteId)[0] ?? null));

        $request['iYAcadId'] = is_null($request->iYAcadId)
            ? null
            : (is_numeric($request->iYAcadId)
                ? $request->iYAcadId
                : ($this->hashids->decode($request->iYAcadId)[0] ?? null));

        $request['iSedeId'] = is_null($request->iSedeId)
            ? null
            : (is_numeric($request->iSedeId)
                ? $request->iSedeId
                : ($this->hashids->decode($request->iSedeId)[0] ?? null));


        $parametros = [$request->iEstudianteId, $request->iYAcadId, $request->iSedeId];

        try {
            $data = DB::select('exec aula.Sp_SEL_notificacion_estudiante ?,?,?', $parametros);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }
}
