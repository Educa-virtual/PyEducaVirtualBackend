<?php

namespace App\Http\Controllers\seg;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Requests\seg\SolicitarRegistroUsuarioRequest;
use App\Services\seg\SolicitudesRegistroUsuarioService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SolicitudRegistroUsuarioController
{

    public function registrarSolicitud(SolicitarRegistroUsuarioRequest $request)
    {
        try {
            SolicitudesRegistroUsuarioService::registrarSolicitud($request);
            return FormatearMensajeHelper::ok('Se ha registrado su solicitud y se ha enviado un correo de confirmación');
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function obtenerListaSolicitudes(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            $data = SolicitudesRegistroUsuarioService::obtenerListaSolicitudesRegistro($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
