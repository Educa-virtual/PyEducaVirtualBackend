<?php

namespace App\Repositories\evaluaciones;

use App\Models\eval\BancoPreguntas;
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
            $params['iBancoIds'] ?? '',
            $params['iEvalucionId'] ?? 0,
            $params['idEncabPregId'] ?? 0,
            $params['iGradoId'] ?? 0
        ];
       
        $preguntasDB = DB::select('exec eval.SP_SEL_bancoPreguntas ?,?,?,?,?,?,?,?,?,?
        ', $params);
        $preguntas = (new BancoPreguntas())->procesarPreguntas($preguntasDB);
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

        $result = DB::select('exec eval.SP_INS_UPD_bancoPregunta @_iBancoId = ?
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

        $result = DB::select('exec eval.SP_INS_UPD_alternativaPregunta
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
