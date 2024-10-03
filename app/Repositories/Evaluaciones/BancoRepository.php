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
            $params['busqueda'] ?? '',
            $params['iDocenteId'],
            $params['iCurrContId'],
            $params['iNivelCicloId'],
            $params['iTipoPregId'] ?? 0,
            $params['iBancoIds '] ?? ''
        ];

        $preguntasDB = DB::select('EXEC eval.Sp_SEL_banco_preguntas 
            @_iCursoId   = ?
            , @_busqueda = ?
            , @_iCurrContId = ?
            , @_iNivelCicloId = ?
            , @_iDocenteId = ?
            , @_iTipoPregId = ?
            , @_iBancoIds = ?
        ', $params);

        $preguntas = [];
        foreach ($preguntasDB as $item) {
            $item->preguntas = json_decode($item->preguntas);
            if ($item->idEncabPregId == -1) {
                if (is_array($item->preguntas)) {
                    $preguntas = array_merge($preguntas, $item->preguntas);
                }
            } else {
                array_push($preguntas, $item);
            }
        }
        return $preguntas;
    }
}
