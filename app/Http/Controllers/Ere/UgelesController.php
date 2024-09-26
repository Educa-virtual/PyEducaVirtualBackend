<?php

namespace App\Http\Controllers;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

use Exception;
use Illuminate\Http\Request;

class UgelesController extends ApiController
{
    //
    public function obtenerUgeles()
    {
        $campos = 'iUgelId,cUgelNombre';
        $where = '';

        $params = [
            'acad',
            'ugeles',
            $campos,
            $where
        ];
        try {
            $preguntas = DB :: select('EXEC grl.sp_SEL_DesdeTabla_Where
                @nombreEsquema = ?,
                @nombreTabla = ?,    
                @campos = ?,        
                @condicionWhere = ?
            ',$params);

             return $this->successResponse(
                $preguntas,
                'Datos obtenidos correctamente'
            );
        }
        catch (Exception $e) {
            return $this->errorResponse($e,'Erro No!');
        }
    } 
}
