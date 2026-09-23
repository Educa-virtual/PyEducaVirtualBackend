<?php

namespace App\Http\Controllers\acad;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\acad\Docente;
use App\Models\grl\Persona;
use App\Services\seg\UsuariosService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DocenteController extends Controller
{
    public function buscarDocente(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Docente::selDocente($request);

            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function listarDocentes(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Docente::selDocentes($request);

            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarDocente(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            if ($request->iPersId == null || $request->iPersId == 0) {
                $persona = Persona::insPersonas($request);
                $request->merge([
                    'iPersId' => $persona['iPersId'],
                ]);
            } else {
                Persona::updPersonas($request);
            }
            // Crear credencial de docente
            $credencial = UsuariosService::registrarUsuario($request);
            $request->merge([
                'iCredId' => $credencial->iCredId,
                'iPerfilId' => Perfil::DOCENTE->value,
            ]);
            UsuariosService::insPerfil($request);
            $data = Docente::insDocente($request);

            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarDocente(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            if ($request->iPersId == null || $request->iPersId == 0) {
                $persona = Persona::insPersonas($request);
                $request->merge([
                    'iPersId' => $persona['iPersId'],
                ]);
            } else {
                Persona::updPersonas($request);
            }
            $data = Docente::updDocente($request);

            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarDocenteEstado(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Docente::updDocenteEstado($request);

            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarDocente(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Docente::delDocente($request);

            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
