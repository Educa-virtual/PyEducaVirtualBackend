<?php

namespace App\Http\Controllers\Ere;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Support\Facades\DB;

class NivelTipoController extends ApiController
{
    //
    public function obtenerNivelTipo()
    {

        $campos = 'iNivelTipoId,cNivelTipoNombre';
        $where = 'iNivelTipoId IN (3,4)';


        $params = [
            'acad',
            'nivel_tipos',
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
