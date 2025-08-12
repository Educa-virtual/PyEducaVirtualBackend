<?php

namespace App\Http\Controllers\bienestar;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\bienestar\EncuestaBienestar;
use App\Services\ParseSqlErrorService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EncuestaBienestarController extends Controller
{
    private array $administran = [
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::ASISTENTE_SOCIAL,
        Perfil::DIRECTOR_IE,
        Perfil::SUBDIRECTOR_IE,
    ];

    private array $visualizan = [
        Perfil::ESTUDIANTE,
        Perfil::APODERADO,
    ];

    public function listarEncuestas(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->administran, $this->visualizan)]);
            $data = EncuestaBienestar::selEncuestas($request);
            return FormatearMensajeHelper::ok('se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function crearEncuesta(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->administran, [Perfil::APODERADO])]);
            $data = EncuestaBienestar::selEncuestaParametros($request);
            return FormatearMensajeHelper::ok('se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verEncuesta(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->administran, $this->visualizan)]);
            $data = EncuestaBienestar::selEncuesta($request);
            return FormatearMensajeHelper::ok('se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarEncuesta(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->administran]);
            $data = EncuestaBienestar::insEncuesta($request);
            return FormatearMensajeHelper::ok('se guardó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarEncuesta(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->administran]);
            $data = EncuestaBienestar::updEncuesta($request);
            return FormatearMensajeHelper::ok('se actualizó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarEncuestaEstado(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->administran]);
            $data = EncuestaBienestar::updEncuestaEstado($request);
            return FormatearMensajeHelper::ok('se actualizó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarEncuesta(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->administran]);
            $data = EncuestaBienestar::delEncuesta($request);
            return FormatearMensajeHelper::ok('se eliminó la encuesta', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function obtenerPoblacionObjetivo(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->administran, [Perfil::APODERADO])]);
            $data = EncuestaBienestar::selPoblacionObjetivo($request);
            return FormatearMensajeHelper::ok('se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

}
