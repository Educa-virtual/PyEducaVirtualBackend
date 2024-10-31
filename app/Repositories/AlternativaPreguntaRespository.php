<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class AlternativaPreguntaRespository
{

    public function getAllByPreguntaId($id)
    {
        $campos = 'iPreguntaId,iAlternativaId,cAlternativaDescripcion,cAlternativaLetra,bAlternativaCorrecta,cAlternativaExplicacion';

        $where = " iPreguntaId = {$id}";
        $params = [
            'ere',
            'alternativas',
            $campos,
            $where
        ];

        $alternativas = DB::select(
            'EXEC grl.sp_SEL_DesdeTabla_Where 
                @nombreEsquema = ?,
                @nombreTabla = ?,    
                @campos = ?,        
                @condicionWhere = ?',
            $params
        );
        return $alternativas;
    }


    public function guardarActualizarAlternativa($params)
    {
        $resp = DB::select('exec ere.SP_INS_UPD_alternativa_pregunta
                @_iAlternativaId = ?
                , @_iPreguntaId = ?
                , @_cAlternativaDescripcion = ?
                , @_cAlternativaLetra = ?
                , @_bAlternativaCorrecta = ?
                , @_cAlternativaExplicacion = ?
            ', $params);

        return $resp;
    }
}
