<?php

namespace App\Http\Controllers\Evaluaciones;

use App\Http\Controllers\ApiController;
use App\Repositories\Evaluaciones\BancoRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BancoPreguntasController extends ApiController
{

    public function obtenerBancoPreguntas(Request $request)
    {

        $params = [
            'iCursoId' => $request->iCursoId,
            'iDocenteId' => $request->iDocenteId,
            'iCurrContId' => $request->iCurrContId,
            'iNivelCicloId' => $request->iNivelCicloId,
            'busqueda' => $request->busqueda ?? '',
            'iTipoPregId' => $request->iTipoPregId ?? 0
        ];

        try {
            $resp = BancoRepository::obtenerPreguntas($params);
            return $this->successResponse($resp, 'Datos obtenidos correctamente');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Error al obtener los datos');
        }
    }

    public function guardarActualizarBancoPreguntas(Request $request)
    {
        $fecha = '';
        $params = [
            $request->iBancoId,
            $request->iDocenteId,
            $request->iTipoPregId,
            $request->iCurrContId,
            // fecha
            $request->cBancoPregunta,
            $fecha,
            $request->cBancoTextoAyuda,
            $request->nBancoPuntaje,
            $request->idEncabPregId,
            $request->iCursoId,
            $request->iNivelCicloId
        ];

        try {
            $resp = DB::select('exec ere.Sp_INS_UPD_banco_pregunta
                @_iBancoId = ?
                , @_iDocenteId = ?
                , @_iTipoPregId = ?
                , @_iCurrContId = ?
                , @_dtBancoCreacion = ?
                , @_cBancoPregunta = ?
                , @_dtBancoTiempo = ?
                , @_cBancoTextoAyuda = ?
                , @_nBancoPuntaje = ?
                , @_idEncabPregId = ?
                , @_iCursoId = ?
                , @_iNivelCicloId = ?
            ');
            if (count($resp) == 0) {
                return $this->errorResponse(null, 'Error al guardar los cambios');
            }
            $resp = $resp[0];
            return $this->successResponse($resp, $resp->mensaje);
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al guardar los cambios');
        }
    }
}
