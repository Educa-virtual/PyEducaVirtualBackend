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


    public static function guardarActualizarPregunta($data)
    {
        $params = [
            $data['iBancoId'],
            $data['iDocenteId'],
            $data['iTipoPregId'],
            $data['iCurrContId'],
            $data['dtBancoCreacion'],
            $data['cBancoPregunta'],
            $data['dtBancoTiempo'],
            $data['cBancoTextoAyuda'],
            $data['nBancoPuntaje'],
            $data['idEncabPregId'],
            $data['iCursoId'],
            $data['iNivelCicloId']
        ];

        $result = DB::select('EXEC eval.Sp_INS_UPD_pregunta @_iBancoId = ?
            , @_iDocenteId  = ?
            , @_iTipoPregId  = ?
            , @_iCurrContId  = ?
            , @_dtBancoCreacion  = ?
            , @_cBancoPregunta  = ?
            , @_dtBancoTiempo  = ?
            , @_cBancoTextoAyuda = ?
            , @_nBancoPuntaje = ?
            , @_idEncabPregId  = ?
            , @_iCursoId  = ?
            , @_iNivelCicloId = ?
        ', $params);
        return $result;
    }
}
