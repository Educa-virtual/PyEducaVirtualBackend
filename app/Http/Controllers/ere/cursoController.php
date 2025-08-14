<?php

namespace App\Http\Controllers\ere;

use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class cursoController extends ApiController
{
    public function obtenerCursos(Request $request)
    {

        $campos = 'iCursoId,iCurrId,cCursoNombre,cCursoDescripcion,cGradoAbreviacion,cSessionNombre';

        $cCursos = $request->cCursos ?? 0;
        $where = "cCursos = {$cCursos}";

        $params = [
            'acad',
            'cursos',
            $campos,
            $where
        ];

        try {
            $preguntas = DB::select('EXEC grl.sp_SEL_DesdeTabla_Where 
                @nombreEsquema = ?,
                @nombreTabla = ?,    
                @campos = ?,        
                @condicionWhere = ?
            ', $params);
            return $this->successResponse(
                $preguntas,
                'Datos obtenidos correctamente'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al obtener los datos');
        }
    }
}
