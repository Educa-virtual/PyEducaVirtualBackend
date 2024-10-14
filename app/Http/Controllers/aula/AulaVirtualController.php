<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AulaVirtualController extends ApiController
{

    public function guardarActividad(Request $request)
    {
        $params = [
            2,
            $request->iDocenteId,
            $request->cTareaTitulo,
            $request->cTareaDescripcion,
            '',
            $request->cTareaIndicaciones,
            0,
            0,
            0,
            null,
            null,
            '',
            1,
            null
        ];

        try {
            $resp = DB::select('EXEC [aula].[SP_INS_InsertActividades]
                    @iProgActId  = ? ,
                    @iDocenteId = ? ,
                    @cTareaTitulo = ?,
                    @cTareaDescripcion = ?,
                    @cTareaArchivoAdjunto = ?,
                    @cTareaIndicaciones = ?,
                    @bTareaEsEvaluado = ?,
                    @bTareaEsRestringida = ?,
                    @bTareaEsGrupal = ?,
                    @dtTareaInicio = ?,
                    @dtTareaFin = ?,
                    @cTareaComentarioDocente = ?,
                    @iEstado = ?,
                    @iSesionId = ?
            ', $params);
            return $this->successResponse($resp, 'Datos guardados correctamente');
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al guardar la actividad');
        }
    }
}
