<?php

namespace App\Http\Controllers\aula;

use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificacionEstudianteController extends Controller
{

    public function mostrar_notificacion(Request $request)
    {

        $iEstudianteId = VerifyHash::decodes($request->iEstudianteId);
        $parametros = [$iEstudianteId, $request->iYAcadId, $request->iSedeId];

        try {
            $data = DB::select('exec aula.Sp_SEL_notificacion_estudiante ?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = 200;
        } catch (Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }
}
