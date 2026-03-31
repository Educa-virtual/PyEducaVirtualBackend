<?php

namespace App\Http\Controllers\ere;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Support\Facades\DB;

class InstitucionesEducativasController extends ApiController
{
    //
    public function obtenerInstitucionesEducativas(Request $request)
    {
        try {
            $params = [
                $request->header('iCredEntPerfId'),
                $request->iUgelId,
            ];
            $placeholders = implode(',', array_fill(0, count($params), '?'));
            $preguntas = DB::select("EXEC ere.SP_SEL_instituciones $placeholders", $params);
            return $this->successResponse(
                $preguntas,
                'Datos obtenidos correctamente'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al obtener los datos');
        }
    }
}
