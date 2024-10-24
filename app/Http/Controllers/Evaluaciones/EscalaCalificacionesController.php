<?php

namespace App\Http\Controllers\Evaluaciones;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EscalaCalificacionesController extends ApiController
{
    public function index(Request $request)
    {
        $data =  DB::table('eval.escala_calificaciones')->get();
        foreach ($data as $key => $item) {
            $data[$key]->iEscalaCalifId = (int) $item->iEscalaCalifId;
        }
        return $this->successResponse($data, 'Datos obtenidos correctamente');
    }
}
