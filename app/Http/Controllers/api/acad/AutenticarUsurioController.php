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
        
        $campo = 'iEspecialistaId,cPersNombre,cPersDocumento';
        $where = '04431751';
        
        $params =[
            'acad',
            'especialistas_UGEL',
            $campo,
            $where
        ];

        try { 
            $preguntas = DB ::select('EXEC acad.SP_SEL_ObtenerDni 
               
                @cPersDocumento= ?
            
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
