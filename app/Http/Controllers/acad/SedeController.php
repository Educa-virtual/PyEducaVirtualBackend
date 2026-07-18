<?php

namespace App\Http\Controllers\acad;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Enums\Perfil;
use App\Models\acad\Sede;

class SedeController extends Controller
{
    public static function listarSedes(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO]]);
            $data = Sede::selSedes($request);
            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public static function guardarSede(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO]]);
            $data = Sede::insSede($request);
            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public static function actualizarSede(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO]]);
            $data = Sede::updSede($request);
            return FormatearMensajeHelper::ok('Se actualizó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public static function eliminarSede(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO]]);
            $data = Sede::delSede($request);
            return FormatearMensajeHelper::ok('Se borró la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}