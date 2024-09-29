<?php

namespace App\Http\Controllers\Ere;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DesempenosController extends ApiController
{
    public function obtenerDesempenos(Request $request)
    {

        $params = [
            $request->iCursoId,
            $request->iNivelTipoId,
            $request->iEvaluacionId ?? 0,
            $request->iCompCursoId ?? 0,
            $request->iCapacidadId ?? 0
        ];


        try {
            $desempenos = DB::select(
                'EXEC acad.Sp_SEL_desempenos @_iCursoId = ?
                , @_InivelTipoId  = ?
                , @_iEvaluacionId = ?
                , @_iCompCursoId = ?
                , @_iCapacidadId = ?',
                $params
            );

            return $this->successResponse($desempenos, 'Datos obtenidos correctamente');
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al obtener los datos');
        }
    }
}
