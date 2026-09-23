<?php

namespace App\Http\Controllers\eval;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\eval\EscalaCalificaciones;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EscalaCalificacionesController extends Controller
{
    public function listarEscalaCalificaciones(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO]]);
            $data = EscalaCalificaciones::selEscalaCalificaciones($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarEscalaCalificaciones(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO]]);
            $data = EscalaCalificaciones::insEscalaCalificaciones($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarEscalaCalificaciones(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DOCENTE]]);
            $data = EscalaCalificaciones::updEscalaCalificaciones($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
