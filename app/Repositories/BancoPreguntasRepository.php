<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class BancoPreguntasRepository
{

    public function obtenerBancoPreguntasByParams($params)
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
}
