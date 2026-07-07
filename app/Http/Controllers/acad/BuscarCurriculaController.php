<?php

namespace App\Http\Controllers\acad;

use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuscarCurriculaController extends Controller
{
    public function Curricula(Request $request)
    {

        $iDocenteId = VerifyHash::decodes($request->iDocenteId);

        $solicitud = [
            'buscar_curricula',
            $iDocenteId ?? null,
            $request->iYAcadId ?? null,
            $request->iIieeId ?? null,
            $request->iSedeId ?? null,
        ];

        $consulta = 'execute acad.Sp_SEL_buscar_cursos '.str_repeat('?,', count($solicitud) - 1).'?';

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

    public function obtenerActividad()
    {
        $solicitud = [
            'buscar_tipo_actividad',
            null,
            null,
            null,
            null,
        ];

        $consulta = 'execute acad.Sp_SEL_buscar_cursos '.str_repeat('?,', count($solicitud) - 1).'?';

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

    public function CurriculaHorario(Request $request)
    {

        $iDocenteId = VerifyHash::decodes($request->iDocenteId);

        $solicitud = [
            2,
            $iDocenteId ?? null,
            $request->iYAcadId ?? null,
            null,
            null,
            null,
            null,
            null,
            null,
            $request->iSedeId ?? null,
            $request->iIieeId ?? null,
        ];

        $consulta = 'execute acad.Sp_SEL_buscar_cursos_horario '.str_repeat('?,', count($solicitud) - 1).'?';

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
