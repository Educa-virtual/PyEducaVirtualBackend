<?php

namespace App\Http\Controllers\bienestar;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\bienestar\SeguimientoBienestar;
use Exception;
use Illuminate\Http\Request;

class SeguimientoBienestarController extends Controller
{
    public function crearSeguimiento(Request $request)
    {
        try {
            $data = SeguimientoBienestar::selSeguimientoParametros($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verSeguimientosPersona(Request $request)
    {
        try {
            $data = SeguimientoBienestar::selSeguimientosPersona($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verSeguimientos(Request $request)
    {
        try {
            $data = SeguimientoBienestar::selSeguimientos($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarSeguimiento(Request $request)
    {
        try {
            $data = SeguimientoBienestar::insSeguimiento($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarSeguimiento(Request $request)
    {
        try {
            $data = SeguimientoBienestar::updSeguimiento($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verSeguimiento(Request $request)
    {
        try {
            $data = SeguimientoBienestar::selSeguimiento($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarSeguimiento(Request $request)
    {
        try {
            $data = SeguimientoBienestar::delSeguimiento($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verDatosPersona(Request $request)
    {
        try {
            $data = SeguimientoBienestar::selDatosPersona($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
