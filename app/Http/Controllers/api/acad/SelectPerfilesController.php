<?php

namespace App\Http\Controllers\api\acad;

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;
use Exception;

class SelectPerfilesController extends ApiController
{
    public function obtenerPerfiles()
    {
        
        /*$campo = 'iEspecialistaId,cPersNombre,cPersDocumento';*/
        $where = '1';
        
        /*$params =[
            $campo,
            $where
        ];*/

        try { 
            $preguntas = DB ::select('EXEC seg.Sp_SEL_credenciales_entidades_perfilesXiCredId ?', [$where]);
           
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
