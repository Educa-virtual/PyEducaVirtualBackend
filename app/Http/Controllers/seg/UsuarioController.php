<?php

namespace App\Http\Controllers\seg;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Requests\seg\LoginUsuarioRequest;
use App\Http\Requests\seg\SolicitarRegistroUsuarioRequest;
use App\Models\seg\Usuario;
use App\Models\User;
use App\Services\seg\UsuariosService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class UsuarioController
{
    function obtenerListaUsuarios(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            $resultado = UsuariosService::obtenerUsuarios($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $resultado, Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function obtenerPerfilesUsuario($iCredId)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            $data = UsuariosService::obtenerPerfilesUsuario($iCredId);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data, Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function cambiarEstadoUsuario($iCredId, Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            $parametros = [
                $iCredId,
                $request->iCredEstado,
                Auth::user()->iCredId
            ];
            $mensaje = UsuariosService::cambiarEstadoUsuario($parametros);
            return FormatearMensajeHelper::ok($mensaje, null, Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function restablecerClaveUsuario($iCredId)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            $parametros = [
                $iCredId,
                Auth::user()->iCredId
            ];
            UsuariosService::restablecerClaveUsuario($parametros);
            return FormatearMensajeHelper::ok('La contraseña del usuario ha sido restablecida a su nombre de usuario.', null, Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function eliminarPerfilUsuario($iCredId, $iCredEntPerfId)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            UsuariosService::eliminarPerfilUsuario($iCredId, $iCredEntPerfId);
            return FormatearMensajeHelper::ok('El perfil del usuario ha sido eliminado', null, Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function actualizarFechaVigenciaUsuario($iCredId, Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            UsuariosService::actualizarFechaVigenciaUsuario($iCredId, $request);
            return FormatearMensajeHelper::ok('Se ha actualizado la fecha de vigencia de la cuenta', null, Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function registrarUsuario(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            $resultado = UsuariosService::registrarUsuario($request, Auth::user()->iCredId);
            return FormatearMensajeHelper::ok($resultado['mensaje'], $resultado['data']);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function agregarPerfilUsuario($iCredId, Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            UsuariosService::asignarPerfilUsuario($iCredId, $request);
            return FormatearMensajeHelper::ok('Se ha asignado el perfil', null, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
