<?php

namespace App\Http\Controllers\acad;

use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class BuscarCurriculaController extends Controller
{
    public function Curricula(Request $request){

        $iDocenteId = VerifyHash::decodes($request->iDocenteId);

        $solicitud = [
            'buscar_curricula',
            $iDocenteId ?? NULL,
            $request->iYAcadId ?? NULL,
            $request->iIieeId ?? NULL,
            $request->iSedeId ?? NULL,
        ];
        
        $consulta = "execute acad.Sp_SEL_buscar_cursos ".str_repeat('?,',count($solicitud)-1).'?';
        
        try {
            $query = DB::select($consulta, $solicitud);

            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 200;
        } catch (Exception $e) {
            $response = [
                'validated' => true,
                'message' => $e->getMessage(),
                'data' => [],
            ];
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }
    public function obtenerActividad(){
        $solicitud = [
            'buscar_tipo_actividad',
            NULL,
            NULL,
            NULL,
            NULL,
        ];
        
        $consulta = "execute acad.Sp_SEL_buscar_cursos ".str_repeat('?,',count($solicitud)-1).'?';
        
        try {
            $query = DB::select($consulta, $solicitud);
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 200;
        } catch (Exception $e) {
            $response = [
                'validated' => true,
                'message' => $e->getMessage(),
                'data' => [],
            ];
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }
    public function CurriculaHorario(Request $request){

        $iDocenteId = VerifyHash::decodes($request->iDocenteId);

        $solicitud = [
            2,
            $iDocenteId ?? NULL,
            $request->iYAcadId ?? NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            $request->iSedeId ?? NULL,
            $request->iIieeId ?? NULL,
        ];
        
        $consulta = "execute acad.Sp_SEL_buscar_cursos_horario ".str_repeat('?,',count($solicitud)-1).'?';
        
        try {
            $query = DB::select($consulta, $solicitud);
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 200;
        } catch (Exception $e) {
            $response = [
                'validated' => true,
                'message' => $e->getMessage(),
                'data' => [],
            ];
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }
}
