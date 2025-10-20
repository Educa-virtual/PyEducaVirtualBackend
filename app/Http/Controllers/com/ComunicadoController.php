<?php

namespace App\Http\Controllers\com;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\com\Comunicado;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ComunicadoController extends Controller
{
    private $emisores = [
        Perfil::ADMINISTRADOR_DREMO,
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::DIRECTOR_IE,
        Perfil::DOCENTE,
    ];

    private $recipientes = [
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::DIRECTOR_IE,
        Perfil::DOCENTE,
        Perfil::ESTUDIANTE,
        Perfil::APODERADO,
    ];

    public function listarComunicados(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->emisores, $this->recipientes)]);
            $data = Comunicado::selComunicados($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function crearComunicado(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [$this->emisores]);
            $data = Comunicado::selComunicadoParametros($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verComunicado(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->emisores, $this->recipientes)]);
            $data = Comunicado::selComunicado($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarComunicado(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [$this->emisores]);
            $data = Comunicado::insComunicado($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarComunicado(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [$this->emisores]);
            $data = Comunicado::delComunicado($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarComunicado(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [$this->emisores]);
            $data = Comunicado::updComunicado($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function obtenerGrupoCantidad(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->emisores)]);
            $data = Comunicado::selGrupoCantidad($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function buscarPersona(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->emisores]);
            $data = Comunicado::selBuscarPersona($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
