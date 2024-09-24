<?php

namespace App\Http\Controllers\Evaluaciones;

use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoPreguntaController extends ApiController
{
    public function obtenerTipoPreguntas(Request $request)
    {

        $campos = 'iTipoPregId, cTipoPregDescripcion';
        $where = '';
        $params = [
            'eval',
            'tipo_preguntas',
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
