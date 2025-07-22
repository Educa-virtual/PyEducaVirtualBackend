<?php

namespace App\Http\Controllers\ere;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CapacidadesController extends ApiController
{

    public function obtenerCapacidades(Request $request)
    {
        $campos = 'iCapacidadId, cCapacidadDescripcion';
        $where = '';
        $params = [
            'ere',
            'capacidades',
            $campos,
            $where
        ];

        $iCompentenciaId = (int) $request->iCompentenciaId;

        if ($iCompentenciaId !== 0) {
            $where .= " AND iCompentenciaId = {$iCompentenciaId}";
        }

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
