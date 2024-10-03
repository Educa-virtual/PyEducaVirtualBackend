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
        
        /*$campo = 'iEspecialistaId,cPersNombre,cPersDocumento';*/
        $where = '45650699';
        
        /*$params =[
            $campo,
            $where
        ];*/

        try { 
            $preguntas = DB ::select('EXEC acad.SP_SEL_ObtenerDni ?', [$where]);
           
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
