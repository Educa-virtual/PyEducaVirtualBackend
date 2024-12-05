<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class BuscarCurriculaController extends Controller
{
    protected $hashids;
    
    public function __construct(){
        $this->hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
    }

    // Decodifica los id enviados por el frontend
    private function decodificar($id){
        return is_null($id) ? null : (is_numeric($id) ? $id : ($this->hashids->decode($id)[0] ?? null));
    }

    public function Curricula(Request $request){

        $solicitud = [
            'buscar_curricula',
            $request["iDocenteId"] ?? 1,
            $request["iYAcadId"] ?? 3,
        ];
        
        $query = DB::select("EXECUTE acad.Sp_SEL_buscar_cursos ?,?,?",$solicitud);
       
        try {
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
        ];
        
        $query = DB::select("EXECUTE acad.Sp_SEL_buscar_cursos ?,?,?",$solicitud);
       
        try {
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

        $iCursoId = $this->decodificar($request["iCursoId"]);
        $iYAcadId = $this->decodificar($request["iYAcadId"]);
        $iDocenteId = $this->decodificar($request["iDocenteId"]);
        $iSeccionId = $this->decodificar($request["iSeccionId"]);

        $solicitud = [
            'buscar_curso_eventos',
            $iDocenteId,
            $iYAcadId,
            $iCursoId,
            $iSeccionId,
        ];

        $query = DB::select("execute acad.Sp_SEL_buscar_cursos_horario ?,?,?,?,?", $solicitud);
    
        try {
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
