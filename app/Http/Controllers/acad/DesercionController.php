<?php

namespace App\Http\Controllers\acad;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\acad\Desercion;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DesercionController extends Controller
{
    public function listarDeserciones(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Desercion::selDeserciones($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function guardarDesercion(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Desercion::insDesercion($request);
            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarDesercion(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Desercion::updDesercion($request);
            return FormatearMensajeHelper::ok('Se actualizó la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verDesercion(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Desercion::selDesercion($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarDesercion(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Desercion::delDesercion($request);
            return FormatearMensajeHelper::ok('Se eliminó la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
