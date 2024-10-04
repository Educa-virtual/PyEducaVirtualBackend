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

        $preguntasDB = DB::select('exec ere.Sp_SEL_banco_preguntas @_iCursoId = ?,
             @_busqueda = ?, @_iTipoPregId = ?, @_bPreguntaEstado = ?, @_iPreguntasIds = ?
            ', $params);
        $preguntas = [];
        foreach ($preguntasDB as $item) {
            $item->preguntas = json_decode($item->preguntas);
            if (gettype($item->bPreguntaEstado) === 'string') {
                $item->bPreguntaEstado = (bool) $item->bPreguntaEstado;
            }
            if ($item->iEncabPregId == -1) {
                if (is_array($item->preguntas)) {
                    $preguntas = array_merge($preguntas, $item->preguntas);
                }
            } else {
                array_push($preguntas, $item);
            }
        }

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
            $data['iColumnValue'],
            $data['cColumnName'] ?? 'iEspecialistaId',
            $data['cSchemaName'] ?? 'ere'
        ];

        $result = DB::select(
            'exec ere.Sp_INS_UPD_encabezado_pregunta @_iEncabPregId  = ?
                , @_cEncabPregTitulo = ?
                , @_cEncabPregContenido = ?
                , @_iCursoId  = ?
                , @_iNivelGradoId  = ?
                , @_iColumnValue  = ?
                ',
            $params
        );

        return $result;
    }

    public static function obtenerCabecerasPregunta($params)
    {
        $campos = 'cEncabPregTitulo, cEncabPregContenido';
        $where = '1=1 ';
        $where .= " AND iCursoId = {$params['iCursoId']}";
        $schema =  $params['schema'] ?? 'ere';
        if ($schema === 'ere') {
            $campos .= ' ,iEncabPregId';
            $where .= " AND iNivelGradoId = {$params['iNivelGradoId']}";
            $where .= " AND iEspecialistaId = {$params['iEspecialistaId']}";
        }

        if ($schema === 'eval') {
            $campos .= ' ,idEncabPregId';
            $where .= " AND iNivelCicloId = {$params['iNivelCicloId']}";
            $where .= " AND iDocenteId = {$params['iDocenteId']}";
        }

        $params = [
            $schema,
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
