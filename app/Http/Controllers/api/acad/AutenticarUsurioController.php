<?php

namespace App\Http\Controllers\api\acad;

use App\Http\Controllers\ApiController; 
use Illuminate\Support\Facades\DB;

use Exception;
use Illuminate\Http\Request;

class AutenticarUsurioController extends ApiController
{
    public function obtenerAutenticacion(){
        $campo = 'iEspecialistaId,cPersNombre,cPersDocumento';
        $where = '04431751';
        dd($campo);
        $params =[
            'acad',
            'V_EspecialistaUgel',
            $campo,
            $where
        ];

        try { 
            $pregunta = DB ::select('grl.sp_SEL_DesdeTablaOVista
                @nombreEsquema = ?,  
                @nombreObjeto = ?,
                @campos=?,         
                @condicionWhere = ?
        
            ',$params);
            /*dd($pregunta);*/
            return $this->successResponse(
                $pregunta,
                'Datos Obtenidos Correctamente'
            );
        }
        catch (Exception $e){

            return $this->errorResponse($e,'Error Ups!');
        }
    }
}
