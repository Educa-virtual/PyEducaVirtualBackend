<?php

namespace App\Http\Controllers\bienestar;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\bienestar\FichaSalud;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FichaSaludController extends Controller
{
    private $registran = [
        Perfil::ESTUDIANTE,
        Perfil::APODERADO,
        Perfil::DOCENTE,
        Perfil::DIRECTOR_IE,
        Perfil::SUBDIRECTOR_IE,
        Perfil::ASISTENTE_SOCIAL,
    ];

    public function actualizarFichaSalud(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);
            $data = FichaSalud::updFichaSalud($request);
            return FormatearMensajeHelper::ok('Se actualizó la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verFichaSalud(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);
            $data = FichaSalud::selfichaSalud($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
