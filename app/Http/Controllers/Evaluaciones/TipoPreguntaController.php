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

        $bancoTipo = $request->bancoTipo ?? 'ere';

        $where = '1=1 ';

        if ($bancoTipo === 'ere') {
            $where .= ' AND iTipoPregId <> 3';
        }

        $campos = 'iTipoPregId, cTipoPregDescripcion';
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

            foreach ($preguntas as &$pregunta) {
                $pregunta->iTipoPregId  = (int) $pregunta->iTipoPregId;
            }

            return $this->successResponse(
                $preguntas,
                'Datos obtenidos correctamente'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al obtener los datos');
        }
    }
}
