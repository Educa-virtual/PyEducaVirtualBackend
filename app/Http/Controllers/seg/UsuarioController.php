<?php

namespace App\Http\Controllers\seg;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Models\seg\Usuario;
use App\Services\grl\PersonasService;
use App\Services\seg\UsuariosService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class UsuarioController
{
    public function crearUsuario(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR, Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Usuario::selCredencialParametros($request);

            return FormatearMensajeHelper::ok('Se ha creado el usuario', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function listarUsuarios(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR, Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = UsuariosService::obtenerUsuarios($request);

            return FormatearMensajeHelper::ok('Datos obtenidos', $data, Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function obtenerPerfilesUsuario(Request $request, $iCredId)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR, Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Usuario::selPerfilesUsuario($iCredId, $request);

            return FormatearMensajeHelper::ok('Datos obtenidos', $data, Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function cambiarEstadoUsuario(Request $request, $iCredId)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            $parametros = [
                'iCredId' => $iCredId,
                'iCredEstado' => $request->iCredEstado,
                'iCredEntPerfId' => $request->header('iCredEntPerfId'),
            ];
            $mensaje = Usuario::updCredencialEstado((object) $parametros);

            return FormatearMensajeHelper::ok($mensaje, null, Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function restablecerClaveUsuario(Request $request, $iCredId)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR, Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $request->merge([
                'iCredId' => $iCredId,
            ]);
            Usuario::updCredencialPassword($request);

            return FormatearMensajeHelper::ok('La contraseña del usuario ha sido restablecida a su nombre de usuario.', null, Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function actualizarPerfilUsuario(Request $request, $iCredId, $iCredEntPerfId)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR, Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $parametros = [
                'iCredEntPerfId' => $iCredEntPerfId,
                'iCredEntPerfEstado' => $request->iCredEntPerfEstado,
            ];
            Usuario::updPerfilEstado((object) $parametros);

            return FormatearMensajeHelper::ok('El perfil del usuario ha sido actualizado', null, Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function actualizarFechaVigenciaUsuario(Request $request, $iCredId)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR, Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $datos = [
                'iCredId' => $iCredId,
                'dtCredCaduca' => $request->dtCredCaduca,
                'iCredEntPerfId' => $request->header('iCredEntPerfId'),
            ];
            Usuario::updFechaVigenciaCuenta((object) $datos);

            return FormatearMensajeHelper::ok('Se ha actualizado la fecha de vigencia', null, Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function registrarUsuario(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR, Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            PersonasService::actualizarPersonaConDataApi($request, $request);
            $resultado = UsuariosService::registrarUsuario($request);

            return FormatearMensajeHelper::ok('Se ha registrado el usuario', $resultado);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function agregarPerfilUsuario(Request $request, $iCredId)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR, Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Usuario::insPerfil($request);

            return FormatearMensajeHelper::ok('Se ha asignado el perfil', $data, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
