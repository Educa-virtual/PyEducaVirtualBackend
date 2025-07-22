<?php

namespace App\Http\Controllers\bienestar;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\bienestar\FichaDiscapacidad;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class FichaDiscapacidadController extends Controller
{
    private $perfiles_permitidos = [
        Perfil::ESTUDIANTE,
        Perfil::APODERADO,
        Perfil::DOCENTE,
        Perfil::DIRECTOR_IE,
    ];

    public function guardarFichaDiscapacidad(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            $data = FichaDiscapacidad::insFichaDiscapacidad($request);
            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarFichaDiscapacidad(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', [$this->perfiles_permitidos)];
            $data = FichaDiscapacidad::updFichaDiscapacidad($request);
            return FormatearMensajeHelper::ok('Se actualizó la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verFichaDiscapacidad(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            $data = FichaDiscapacidad::selfichaDiscapacidad($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
