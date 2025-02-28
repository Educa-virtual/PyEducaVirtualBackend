<?php

namespace App\Repositories;

use Hashids\Hashids;
use Illuminate\Support\Facades\DB;

class PreguntasRepository
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public static function formatearValor($valor)
    {
        if (is_numeric($valor)) {
            return $valor;
        }
        // Se pueden usar addslashes o mysqli_real_escape_string según el caso
        return "'" . addslashes($valor) . "'";
    }


    public static function contarPreguntasEre($preguntas)
    {
        $cantidad = 0;
        foreach ($preguntas as $indexPregunta => $pregunta) {
            if ($pregunta->iEncabPregId=='-1') {
                $cantidad++;
            } else {
                foreach ($pregunta->preguntas as $indexSubPregunta => $subPregunta) {
                    $cantidad++;
                }
            }
        }
        return $cantidad;
    }

    public static function obtenerBancoPreguntasByParams($params)
    {
        $params = [
            $params['iCursosNivelGradId'] ?? 0,
            $params['busqueda'] ?? '',
            $params['iTipoPregId'] ?? 0,
            $params['bPreguntaEstado'] ?? -1,
            $params['ids'] ?? '',
            $params['iEncabPregId'] ?? 0,
            $params['iEvaluacionId'] ?? 0
        ];

        $preguntasDB = DB::select('exec ere.SP_SEL_bancoPreguntas @_iCursosNivelGradId = ?,
             @_busqueda = ?, @_iTipoPregId = ?, @_bPreguntaEstado = ?, @_iPreguntasIds = ?,
             @_iEncabPregId = ?, @_iEvaluacionId = ?
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
    public static function obtenerBancoPreguntas($params)
    {

        $params = [
            $params['BancoId'] ?? 0,
            $params['iDocenteId'] ?? '',
            $params['iCursoId'] ?? '',
        ];

        $preguntasDB = DB::select('exec eval.SP_SEL_preguntasEvaluacionx @BancoId = ?,
             @iDocenteId = ?, @iCursoId = ?
            ', $params);
        $preguntas = [];
        foreach ($preguntasDB as $item) {
            $item->alternativas = json_decode($item->alternativas);
            array_push($preguntas, $item);
        }

        return $preguntas;
    }

    public static function guardarActualizarPreguntaEncabezado($data)
    {
        $params = [
            $data['iEncabPregId'],
            $data['cEncabPregTitulo'] ?? '',
            $data['cEncabPregContenido'] ?? '',
            //$data['iCursoId'],
            $data['iCursosNivelGradId'],
            $data['iNivelGradoId'],
            $data['iColumnValue'],
            $data['cColumnName'] ?? 'iEspecialistaId',
            $data['cSchemaName']
        ];
        // , @_iCursoId = ?
        $result = DB::select(
            'exec ere.SP_INS_UPD_encabezadoPregunta @_iEncabPregId  = ?
                , @_cEncabPregTitulo = ?
                , @_cEncabPregContenido = ?
                , @_iCursosNivelGradId = ?
                , @_iNivelGradoId  = ?
                , @_iColumnValue  = ?
                , @_cColumnName = ?
                , @_cSchemaName = ?
                ',
            $params
        );

        return $result;
    }

    public static function obtenerCabecerasPregunta($params)
    {

        $campos = 'cEncabPregTitulo, cEncabPregContenido';
        $where = '1=1 ';
        //$where .= " AND iCursoId = {$params['iCursoId']}";//Aqui se paso en Eval
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
            $where .= " AND iCursoId = {$params['iCursoId']}";
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
