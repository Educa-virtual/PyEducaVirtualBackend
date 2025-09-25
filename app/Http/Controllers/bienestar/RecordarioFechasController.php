<?php

namespace App\Http\Controllers\bienestar;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\bienestar\RecordatorioFechas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RecordarioFechasController extends Controller
{
    private $perfiles_permitidos = [
        Perfil::ESTUDIANTE,
        Perfil::DOCENTE,
        Perfil::DIRECTOR_IE,
        Perfil::SUBDIRECTOR_IE,
        Perfil::ASISTENTE_SOCIAL,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ADMINISTRADOR_DREMO,
    ];

    public function verFechasEspeciales(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);
            $data = RecordatorioFechas::selCumpleanios($request);
            return FormatearMensajeHelper::ok('se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verRecordatorioPeriodos(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);
            $data = RecordatorioFechas::selRecordatorioPeriodos($request);
            return FormatearMensajeHelper::ok('se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verConfRecordatorio(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);
            $data = RecordatorioFechas::selCumpleaniosConfiguracion($request);
            return FormatearMensajeHelper::ok('se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarConfRecordatorio(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);
            $data = RecordatorioFechas::updCumpleaniosConfiguracion($request);
            return FormatearMensajeHelper::ok('se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
