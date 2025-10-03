<?php

namespace App\Http\Controllers\enc;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\enc\Plantilla;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PLantillaController extends Controller
{
    private $encuestadores = [
        Perfil::ADMINISTRADOR_DREMO,
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::DIRECTOR_IE,
    ];

    public function listarPlantillas(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Plantilla::selPlantillas($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verPlantilla(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Plantilla::selPlantilla($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarPlantilla(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Plantilla::insPlantilla($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarPlantilla(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Plantilla::delPlantilla($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarPlantilla(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Plantilla::updPlantilla($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
