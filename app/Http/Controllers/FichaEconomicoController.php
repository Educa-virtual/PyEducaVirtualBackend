<?php

namespace App\Http\Controllers;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Models\bienestar\FichaEconomico;
use App\Services\ParseSqlErrorService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class FichaEconomicoController extends Controller
{
    private $perfiles_permitidos = [
        Perfil::ESTUDIANTE,
        Perfil::APODERADO,
        Perfil::DOCENTE,
        Perfil::DIRECTOR_IE,
    ];

    public function guardarFichaEconomico(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            $data = FichaEconomico::insfichaEconomico($request);
            return FormatearMensajeHelper::ok('Se guardo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarFichaEconomico(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
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
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            $data = FichaEconomico::selFichaEconomico($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
