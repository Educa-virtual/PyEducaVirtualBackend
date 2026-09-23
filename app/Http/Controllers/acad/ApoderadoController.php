<?php

namespace App\Http\Controllers\acad;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\apo\Apoderado;
use App\Services\acad\MatriculasService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ApoderadoController extends Controller
{
    public function listarApoderados(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE, Perfil::DOCENTE]]);
            $data = Apoderado::selApoderados($request);

            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function guardarApoderado(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            DB::beginTransaction();
            $data = MatriculasService::registrarApoderado($request);
            DB::commit();

            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (\Exception $e) {
            DB::rollBack();

            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarApoderado(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            DB::beginTransaction();
            $data = MatriculasService::actualizarApoderado($request);
            DB::commit();

            return FormatearMensajeHelper::ok('Se actualizó la información', $data);
        } catch (\Exception $e) {
            DB::rollBack();

            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarApoderadoEstado(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Apoderado::updApoderadoEstado($request);

            return FormatearMensajeHelper::ok('Se actualizó la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verApoderado(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE, Perfil::DOCENTE]]);
            $data = Apoderado::selApoderado($request);

            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarApoderado(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Apoderado::delApoderado($request);

            return FormatearMensajeHelper::ok('Se eliminó la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function buscarPersonaApoderado(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Apoderado::selPersonaApoderado($request);

            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function listarEstudiantes(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE, Perfil::APODERADO]]);
            $data = Apoderado::selApoderadoEstudiantes($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
