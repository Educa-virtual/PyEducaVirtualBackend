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
        return $this->successResponse($data, 'Datos obtenidos correctamente');
    }
}
