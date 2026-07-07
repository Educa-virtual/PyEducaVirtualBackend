<?php

namespace App\Http\Controllers\acad;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\acad\ActualizarDistribucionBloqueRequest;
use App\Http\Requests\acad\DistribucionBloqueRequest;
use App\Http\Requests\acad\GuardarDistribucionBloqueRequest;
use App\Http\Requests\acad\ListarDistribucionBloquesRequest;
use App\Models\acad\DistribucionBloque;
use Illuminate\Support\Facades\Gate;

class DistribucionBloqueController extends Controller
{
    public function listarDistribucionBloques(ListarDistribucionBloquesRequest $request)
    {
        try {
            // Permitido para todos los perfiles
            $data = DistribucionBloque::selDistribucionBloques($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verDistribucionBloque(DistribucionBloqueRequest $request)
    {
        try {
            // Permitido para todos los perfiles
            $data = DistribucionBloque::selDistribucionBloque($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarDistribucionBloque(GuardarDistribucionBloqueRequest $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO]]);
            $data = DistribucionBloque::insDistribucionBloque($request);
            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarDistribucionBloque(ActualizarDistribucionBloqueRequest $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO]]);
            $data = DistribucionBloque::updDistribucionBloque($request);
            return FormatearMensajeHelper::ok('Se actualizó la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarDistribucionBloque(DistribucionBloqueRequest $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO]]);
            $data = DistribucionBloque::delDistribucionBloque($request);
            return FormatearMensajeHelper::ok('Se eliminó la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
