<?php

namespace App\Http\Controllers\bienestar;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\bienestar\FichaEconomico;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FichaEconomicoController extends Controller
{
    private $registran = [
        Perfil::ESTUDIANTE,
        Perfil::APODERADO,
        Perfil::DOCENTE,
        Perfil::DIRECTOR_IE,
        Perfil::SUBDIRECTOR_IE,
        Perfil::ASISTENTE_SOCIAL,
    ];

    public function actualizarFichaEconomico(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);
            $data = FichaEconomico::updFichaEconomico($request);
            return FormatearMensajeHelper::ok('Se actualizo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verFichaEconomico(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);
            $data = FichaEconomico::selFichaEconomico($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
