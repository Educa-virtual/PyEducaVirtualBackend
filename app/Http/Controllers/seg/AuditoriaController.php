<?php

namespace App\Http\Controllers\seg;

use App\Enums\Perfil;
use App\Helpers\CollectionStrategy;
use App\Helpers\FormatearMensajeHelper;
use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseHandler;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\seg\AuditoriaFiltroFechaRequest;
use App\Services\seg\AuditoriaService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

class AuditoriaController extends Controller
{
    public function obtenerAccesosAutorizados(AuditoriaFiltroFechaRequest $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            $data = AuditoriaService::obtenerAccesosAutorizados($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function obtenerConsultasDatabase(AuditoriaFiltroFechaRequest $request) {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            $data = AuditoriaService::obtenerConsultasDatabase($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function obtenerConsultasBackend(AuditoriaFiltroFechaRequest $request) {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            $data = AuditoriaService::obtenerConsultasBackend($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function obtenerAccesosFallidos(AuditoriaFiltroFechaRequest $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            $data = AuditoriaService::obtenerAccesosFallidos($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
