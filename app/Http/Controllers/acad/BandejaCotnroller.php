<?php

namespace App\Http\Controllers\acad;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\acad\Bandeja;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BandejaCotnroller extends Controller
{
    public function bandejaEstudiante(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', [[Perfil::ESTUDIANTE]]);
            $data = Bandeja::BandejaEntradaEstudiante($request);
            return FormatearMensajeHelper::ok('Se ha obtenido bandeja de estudiante', $data, Response::HTTP_CREATED); 
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function bandejaDocente(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', [[Perfil::ESTUDIANTE]]);
            $data = Bandeja::BandejaEntradaDocente($request);
            return FormatearMensajeHelper::ok('Se ha obtenido bandeja de docente', $data, Response::HTTP_CREATED); 
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
