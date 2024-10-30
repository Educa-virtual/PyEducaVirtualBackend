<?php

namespace App\Http\Controllers\Ere;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Support\Facades\DB;

class InstitucionesEducativasController extends ApiController
{
    //
    public function obtenerInstitucionesEducativas()
    {

        $campos = 'iIieeId,cIieeCodigoModular,cIieeNombre';
        $where = '';

        $params = [
            'ere',
            'vistaInstitucionEducativa',
            $campos,
            $where

        ];

        try {
            $preguntas = DB::select('EXEC ere.sp_SEL_Instituciones');
            return $this->successResponse(
                $preguntas,
                'Datos obtenidos correctamente'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al obtener los datos');
        }
    }
}
