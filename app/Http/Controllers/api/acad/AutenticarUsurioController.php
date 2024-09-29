<?php

namespace App\Http\Controllers\api\acad;

use App\Http\Controllers\ApiController; 
use Illuminate\Support\Facades\DB;

use Exception;
use Illuminate\Http\Request;

class AutenticarUsurioController extends ApiController
{
    public function obtenerAutenticacion()
    {
        
        $campo = 'iEspecialistaId, cPersNombre, cPersDocumento';
        $where = '45650699';
        
        $params =[
            'acad',
            'V_EspecialistaUgel',
            $campo,
            $where
        ];

        try { 
            $preguntas = DB ::select('EXEC grl.sp_SEL_DesdeTablaOVista
                @nombreEsquema = ?,  
                @nombreObjeto = ?,
                @campos= ?,         
                @condicionWhere = ?
        
            ', $params);
           
            return $this->successResponse(
                $preguntas,
                'Datos Obtenidos Correctamente'
            );
        }
        catch (Exception $e){

            return $this->errorResponse($e,'Error Upssss!');
        }
    }
}
