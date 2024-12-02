<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificacionEstudianteController extends Controller
{
    public function mostrar_notificacion(Request $request){
        $parametros = [1];
   
        try {
            $data = DB::select('exec acad.Sp_SEL_notificacion_estudiante ?', $parametros);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $estado = 500;
        }

        return new JsonResponse($response,$estado);
    }
}
