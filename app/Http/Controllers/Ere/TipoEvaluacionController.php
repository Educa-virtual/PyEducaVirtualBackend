<?php

namespace App\Http\Controllers\ere;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Support\Facades\DB;
class TipoEvaluacionController extends ApiController
{
    //
    public function obtenerTipoEvaluacion()
    {

        $campos = 'idTipoEvalId,cTipoEvalDescripcion';
        $where = '';
       

        $params = [
            'ere',
            'tipo_evaluaciones',
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
