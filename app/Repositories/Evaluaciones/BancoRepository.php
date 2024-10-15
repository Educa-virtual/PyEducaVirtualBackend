<?php

namespace App\Repositories\Evaluaciones;

use Exception;
use Illuminate\Support\Facades\DB;

class BancoRepository
{

    public static function obtenerPreguntas($params)
    {
        $params = [
            $params['iCursoId'] ?? 0,
            $params['busqueda'] ?? '',
            $params['iDocenteId'] ?? 0,
            $params['iCurrContId'] ?? 0,
            $params['iNivelCicloId'] ?? 0,
            $params['iTipoPregId'] ?? 0,
            $params['iBancoIds'] ?? ''
        ];

        $preguntasDB = DB::select('exec eval.Sp_SEL_banco_preguntas 
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
            // $data['dtBancoCreacion'],
            $data['cBancoPregunta'],
            $data['dtBancoTiempo'],
            $data['cBancoTextoAyuda'],
            $data['nBancoPuntaje'],
            $data['idEncabPregId'],
            $data['iCursoId'],
            $data['iNivelCicloId']
        ];

        $result = DB::select('exec eval.Sp_INS_UPD_banco_pregunta @_iBancoId = ?
            , @_iDocenteId  = ?
            , @_iTipoPregId  = ?
            , @_iCurrContId  = ?
            
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

    public static function guardarActualizarAlternativa($params)
    {
        $params = [
            $params['iBancoAltId'],
            $params['iBancoId'],
            $params['cBancoAltLtera'],
            $params['cBancoAltDescripcion'],
            $params['bBancoAltRptaCarrecta'],
            $params['cBancoAltExplicacionRpta']
        ];

        $result = DB::select('exec eval.Sp_INS_UPD_alternativa_pregunta 
            @_iBancoAltId = ?
	        , @_iBancoId = ?
	        , @_cBancoAltLetra = ? 
	        , @_cBancoAltDescripcion = ?
	        , @_bBancoAltRptaCorrecta = ?
	        , @_cBancoAltExplicacionRpta = ?  
        ', $params);
        return $result;
    }
}
