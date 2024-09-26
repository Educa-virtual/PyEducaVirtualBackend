<?php

namespace App\Http\Controllers\Ere;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Support\Facades\DB;
class NivelEvaluacionController extends ApiController
{
    //
    public function obtenerNivelEvaluacion()
    {

        $campos = 'iNivelEvalId,cNivelEvalNombre';
        $where = '';
       

        $params = [
            'ere',
            'nivel_evaluaciones',
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
