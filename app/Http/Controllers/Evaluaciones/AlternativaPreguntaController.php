<?php

namespace App\Http\Controllers\Evaluaciones;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlternativaPreguntaController extends ApiController
{

    public function obtenerAlternativaByPreguntaId(Request $request, $id)
    {
        $campos = 'iPreguntaId,iAlternativaId,cAlternativaDescripcion,cAlternativaLetra,bAlternativaCorrecta,cAlternativaExplicacion';

        $where = " iPreguntaId = {$id}";
        $params = [
            'ere',
            'alternativas',
            $campos,
            $where
        ];

        try {
            $alternativas = DB::select(
                'EXEC grl.sp_SEL_DesdeTabla_Where 
                @nombreEsquema = ?,
                @nombreTabla = ?,    
                @campos = ?,        
                @condicionWhere = ?',
                $params
            );

            return $this->successResponse($alternativas, 'Datos obtenidos correctamente');
        } catch (Exception $e) {

            return $this->errorResponse($e, 'Error al obtener las respuestas');
        }
    }

    public function guardarActualizarAlternativa(Request $request)
    {

        $params = [
            (int) $request->iAlternativaId,
            (int) $request->iPreguntaId,
            $request->cAlternativaDescripcion,
            $request->cAlternativaLetra,
            $request->bAlternativaCorrecto ? 1 : 0,
            $request->cAlternativaExplicacion
        ];

        // return $params;

        try {
            $resp = DB::select('exec ere.Sp_INS_UPD_alternativa_pregunta
                @_iAlternativaId = ?
                , @_iPreguntaId = ?
                , @_cAlternativaDescripcion = ?
                , @_cAlternativaLetra = ?
                , @_bAlternativaCorrecta = ?
                , @_cAlternativaExplicacion = ?
            ', $params);

            $resp = $resp[0];
            return $this->successResponse($resp, $resp->mensaje);
        } catch (Exception $e) {
            $defaultMessage = $this->returnError($e, 'Error al guardar los cambios');
            return $this->errorResponse($e, $defaultMessage);
        }
    }

    public function eliminarAlternativaById(Request $request, $id)
    {
        $params = [
            $id
        ];
        try {
            $resp = DB::select('exec ere.Sp_DEL_alternativa_pregunta @_iAlternativaId = ?', $params);

            $resp = $resp[0];
            return $this->successResponse($resp, $resp->mensaje);
        } catch (Exception $e) {
            $defaultMessage = $this->returnError($e, 'Error al eliminar');
            return $this->errorResponse($e, $defaultMessage);
        }
    }
}
