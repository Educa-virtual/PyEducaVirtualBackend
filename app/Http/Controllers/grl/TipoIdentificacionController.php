<?php

namespace App\Http\Controllers\grl;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\grl\TipoIdentificacion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TipoIdentificacionController extends Controller
{
    private $autorizado = [
        Perfil::ADMINISTRADOR_DREMO,
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::DIRECTOR_IE,
        Perfil::DOCENTE,
    ];

    public function selTipoIdentificacion(){
        try {
            Gate::authorize('tiene-perfil', [$this->autorizado]);
            $data = TipoIdentificacion::selTipoIdentificacion();
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
