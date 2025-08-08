<?php

namespace App\Http\Controllers\bienestar;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\bienestar\SeguimientoBienestar;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SeguimientoBienestarController extends Controller
{
    private $perfiles_permitidos = [
        Perfil::DIRECTOR_IE,
        Perfil::SUBDIRECTOR_IE,
        Perfil::ASISTENTE_SOCIAL,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ADMINISTRADOR_DREMO,
    ];

    public function crearSeguimiento(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);
            $data = SeguimientoBienestar::selSeguimientoParametros($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verSeguimientosPersona(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);
            $data = SeguimientoBienestar::selSeguimientosPersona($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verSeguimientos(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);
            $data = SeguimientoBienestar::selSeguimientos($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarSeguimiento(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);
            $data = SeguimientoBienestar::insSeguimiento($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarSeguimiento(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);
            $data = SeguimientoBienestar::updSeguimiento($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verSeguimiento(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);
            $data = SeguimientoBienestar::selSeguimiento($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarSeguimiento(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);
            $data = SeguimientoBienestar::delSeguimiento($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verDatosPersona(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);
            $data = SeguimientoBienestar::selDatosPersona($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
