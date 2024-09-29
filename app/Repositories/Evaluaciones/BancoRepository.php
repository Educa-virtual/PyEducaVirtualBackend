<?php

namespace App\Repositories\Evaluaciones;

use Exception;
use Illuminate\Support\Facades\DB;

class BancoRepository
{

    public static function obtenerPreguntas($params)
    {
        $params = [
            $params['iCursoId'],
            $params['iDocenteId'],
            $params['iCurrContId'],
            $params['iNivelCicloId'],
            $params['busqueda'] ?? '',
            $params['iTipoPregId'] ?? 0
        ];

        return DB::select('EXEC eval.Sp_SEL_banco_preguntas 
            @_iCursoId   = ?
            , @_iDocenteId = ?
            , @_iCurrContId = ?
            , @_iNivelCicloId = ?
            , @_busqueda = ?
            , @_iTipoPregId = ?
        ', $params);
    }
}
