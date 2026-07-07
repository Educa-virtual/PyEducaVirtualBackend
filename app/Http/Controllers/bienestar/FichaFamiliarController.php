<?php

namespace App\Http\Controllers\bienestar;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\bienestar\FichaFamiliarSaveRequest;
use App\Models\bienestar\FichaFamiliar;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FichaFamiliarController extends Controller
{
    private $registran = [
        Perfil::ESTUDIANTE,
        Perfil::APODERADO,
        Perfil::DOCENTE,
        Perfil::DIRECTOR_IE,
        Perfil::SUBDIRECTOR_IE,
        Perfil::ASISTENTE_SOCIAL,
    ];

    public function listarFichaFamiliares(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);
            $data = FichaFamiliar::selfichasFamiliaresPersonas($request);

            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarFichaFamiliar(FichaFamiliarSaveRequest $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);
            $data = FichaFamiliar::insfichaFamiliar($request);

            return FormatearMensajeHelper::ok('Se guardo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarFichaFamiliar(FichaFamiliarSaveRequest $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);
            $data = FichaFamiliar::updFichaFamiliar($request);

            return FormatearMensajeHelper::ok('Se actualizo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verFichaFamiliar(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);
            $data = FichaFamiliar::selFichaFamiliar($request);

            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarFichaFamiliar(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);
            $data = FichaFamiliar::delFichaFamiliar($request);

            return FormatearMensajeHelper::ok('Se elimino la ficha familiar', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
