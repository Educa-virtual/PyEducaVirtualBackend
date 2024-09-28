<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class PreguntasRepository
{

    public static function obtenerBancoPreguntasByParams($params)
    {

        $params = [
            $params['iCursoId'],
            $params['busqueda'] ?? '',
            $params['iTipoPregId'] ?? 0,
            $params['bPreguntaEstado'] ?? -1,
            $params['ids'] ?? ''
        ];

        $preguntas = DB::select('exec ere.Sp_SEL_banco_preguntas @_iCursoId = ?,
             @_busqueda = ?, @_iTipoPregId = ?, @_bPreguntaEstado = ?, @_iPreguntasIds = ?
            ', $params);

        return $preguntas;
    }

    public static function guardarActualizarPreguntaEncabezado($data)
    {
        $params = [
            $data['iEncabPregId'],
            $data['cEncabPregTitulo'] ?? '',
            $data['cEncabPregContenido'] ?? '',
            $data['iCursoId'],
            $data['iNivelGradoId'],
            $data['iEspecialistaId']
        ];

        $result = DB::select(
            'exec ere.Sp_INS_UPD_encabezado_pregunta @_iEncabPregId  = ?
                , @_cEncabPregTitulo = ?
                , @_cEncabPregContenido = ?
                , @_iCursoId  = ?
                , @_iNivelGradoId  = ?
                , @_iEspecialistaId  = ?
                ',
            $params
        );

        return $result;
    }

    public static function obtenerCabecerasPregunta($params)
    {
        $campos = 'iEncabPregId, cEncabPregTitulo, cEncabPregContenido';
        $where = " iNivelGradoId = {$params['iNivelGradoId']}";
        $where .= " AND iCursoId = {$params['iCursoId']}";
        $where .= " AND iEspecialistaId = {$params['iEspecialistaId']}";

        $params = [
            'ere',
            'encabezado_preguntas',
            $campos,
            $where
        ];

        return DB::select('EXEC grl.sp_SEL_DesdeTabla_Where 
                @nombreEsquema = ?,
                @nombreTabla = ?,    
                @campos = ?,        
                @condicionWhere = ?
            ', $params);
    }
}
