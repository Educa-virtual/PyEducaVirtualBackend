<?php

namespace App\Http\Controllers\doc;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\doc\ActividadesGestion;
use Exception;
use Illuminate\Support\Facades\Gate;

class TiposCargaNoLectivasController extends Controller
{
    public function list()
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DOCENTE, Perfil::DIRECTOR_IE]]);
            $data = ActividadesGestion::obtenerTiposActividades();

            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
