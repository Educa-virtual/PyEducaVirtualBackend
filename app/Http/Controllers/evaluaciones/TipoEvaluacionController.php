<?php

namespace App\Http\Controllers\evaluaciones;

use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoEvaluacionController extends ApiController
{
    public function index(Request $request)
    {
        $campos = 'iTipoEvalId,cTipoEvalNombre,cTipoEvalDescripcion,nTipoEvalPeso';
        $where = '';

        $params = [
            'eval',
            'tipo_evaluaciones',
            $campos,
            $where
        ];

        try {
            $data = DB::select(
                'EXEC grl.sp_SEL_DesdeTabla_Where
                @nombreEsquema = ?,
                @nombreTabla = ?,    
                @campos = ?,        
                @condicionWhere = ?',
                $params
            );
            return $this->successResponse($data, 'Datos obtenidos correctamente');
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al obtener los datos');
        }
    }
}
