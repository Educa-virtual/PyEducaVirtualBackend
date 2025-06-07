<?php

namespace App\Http\Controllers\seg;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Requests\seg\LoginUsuarioRequest;
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
    /**
     * @OA\Get(
     *     path="/api/seg/usuarios",
     *     tags={"Gestión de usuarios"},
     *     summary="Obtiene la lista de usuarios. Se requiere rol de administrador.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(ref="#/components/parameters/iCredEntPerfId"),
     *     @OA\Parameter(
     *         name="opcionBusquedaSeleccionada",
     *         in="query",
     *         required=false,
     *         description="Opción de búsqueda seleccionada",
     *         @OA\Schema(type="string", enum={"documento", "apellidos", "nombres"})
     *     ),
     *     @OA\Parameter(
     *         name="criterioBusqueda",
     *         in="query",
     *         required=false,
     *         description="Criterio de búsqueda de acuerdo a la opción seleccionada",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         required=true,
     *         description="Cantidad de registros a omitir para la paginación",
     *         @OA\Schema(type="integer", example=0)
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         required=true,
     *         description="Cantidad de registros a obtener",
     *         @OA\Schema(type="integer", example=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos obtenidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Datos obtenidos"),
     *             @OA\Property(property="data", type="object", example="Data de los usuarios"),
     *         )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/400"),
     *     @OA\Response(response=403, ref="#/components/responses/403"),
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/seg/usuarios/{iCredId}/perfiles",
     *     tags={"Gestión de usuarios"},
     *     summary="Obtiene los perfiles asignados del usuario especificado. Se requiere rol de administrador.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(ref="#/components/parameters/iCredEntPerfId"),
     *     @OA\Parameter(
     *         name="iCredId",
     *         in="path",
     *         required=true,
     *         description="Id de la credencial del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos obtenidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Datos obtenidos"),
     *             @OA\Property(property="data", type="object", example="Data de los perfiles del usuario"),
     *         )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/400"),
     *     @OA\Response(response=403, ref="#/components/responses/403"),
     * )
     */
    public function obtenerPerfilesUsuario($iCredId)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            $data=UsuariosService::obtenerPerfilesUsuario($iCredId);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data, Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/seg/usuarios/{iCredId}/estado",
     *     tags={"Gestión de usuarios"},
     *     summary="Cambia el estado del usuario especificado. Se requiere rol de administrador.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(ref="#/components/parameters/iCredEntPerfId"),
     *     @OA\Parameter(
     *         name="iCredId",
     *         in="path",
     *         required=true,
     *         description="Id de la credencial del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="iCredEstado",
     *         in="query",
     *         required=true,
     *         description="Nuevo estado del usuario (1: activo, 0: inactivo)",
     *         @OA\Schema(type="integer", enum={1, 0})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El usuario ha sido activado/desactivado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="El usuario ha sido activado/desactivado"),
     *         )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/400"),
     *     @OA\Response(response=403, ref="#/components/responses/403"),
     * )
     */
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

    /**
     * @OA\Patch(
     *     path="/api/seg/usuarios/{iCredId}/password",
     *     tags={"Gestión de usuarios"},
     *     summary="Establece la contraseña del usuario especificado a su nombre de usuario. Se requiere rol de administrador.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(ref="#/components/parameters/iCredEntPerfId"),
     *     @OA\Parameter(
     *         name="iCredId",
     *         in="path",
     *         required=true,
     *         description="Id de la credencial del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contraseña cambiada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="La contraseña del usuario ha sido restablecida a su nombre de usuario"),
     *         )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/400"),
     *     @OA\Response(response=403, ref="#/components/responses/403"),
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/seg/usuarios/{iCredId}/perfiles/{iCredEntPerfId}",
     *     tags={"Gestión de usuarios"},
     *     summary="Elimina un perfil del usuario especificado. Se requiere rol de administrador.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(ref="#/components/parameters/iCredEntPerfId"),
     *     @OA\Parameter(
     *         name="iCredId",
     *         in="path",
     *         required=true,
     *         description="Id de la credencial del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="iCredEntPerfId",
     *         in="path",
     *         required=true,
     *         description="Id del perfil a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Perfil eliminado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="El perfil del usuario ha sido eliminado"),
     *         )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/400"),
     *     @OA\Response(response=403, ref="#/components/responses/403"),
     * )
     */
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

    /**
     * @OA\Patch(
     *     path="/api/seg/usuarios/{iCredId}/fecha-vigencia",
     *     tags={"Gestión de usuarios"},
     *     summary="Actualiza la fecha de vigencia del usuario especificado. Se requiere rol de administrador.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(ref="#/components/parameters/iCredEntPerfId"),
     *     @OA\Parameter(
     *         name="iCredId",
     *         in="path",
     *         required=true,
     *         description="Id de la credencial del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="dtCredCaduca",
     *         in="query",
     *         required=true,
     *         description="Fecha de caducidad de la credencial",
     *         @OA\Schema(type="string", format="date-time", example="2026-05-15 03:10")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Se ha actualizado la fecha de vigencia de la cuenta",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Se ha actualizado la fecha de vigencia de la cuenta"),
     *         )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/400"),
     *     @OA\Response(response=403, ref="#/components/responses/403"),
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/seg/usuarios",
     *     tags={"Gestión de usuarios"},
     *     summary="Registra un usuario. Se requiere rol de administrador.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(ref="#/components/parameters/iCredEntPerfId"),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del usuario a registrar",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"iTipoIdentId", "cPersDocumento","cPersPaterno","cPersNombre","cPersSexo"},
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="iTipoIdentId", type="string", example="1"),
     *                 @OA\Property(property="cPersDocumento", type="string", example="12345678"),
     *                 @OA\Property(property="cPersPaterno", type="string", example="Apellido de prueba"),
     *                 @OA\Property(property="cPersMaterno", type="string", example="Apellido de prueba"),
     *                 @OA\Property(property="cPersNombre", type="string", example="Nombre de prueba"),
     *                 @OA\Property(property="cPersSexo", type="string", example="M"),
     *                 @OA\Property(property="dPersNacimiento", type="string", format="date", example="1988-04-12"),
     *                 @OA\Property(property="iTipoEstCivId", type="string", example="SOLTERO"),
     *                 @OA\Property(property="iNacionId", type="string", example="193"),
     *                 @OA\Property(property="cPersDomicilio", type="string", example="COSTA AZUL MZ.D LOTE.23"),
     *                 @OA\Property(property="iPaisId", type="integer", example=589),
     *                 @OA\Property(property="iDptoId", type="integer", example=17),
     *                 @OA\Property(property="iPrvnId", type="integer", example=149),
     *                 @OA\Property(property="iDsttId", type="integer", example=1494),
     *                 @OA\Property(property="cEstUbigeo", type="string", example="180301")
     *             ),
     *             @OA\Property(property="iSedeId", type="integer", example=0),
     *             @OA\Property(property="iYAcadId", type="integer", example=0),
     *             @OA\Property(property="iPerfilId", type="integer", example=0)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Se ha registrado el usuario",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Se ha registrado el usuario / El usuario ya se encuentra registrado"),
     *         )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/400"),
     *     @OA\Response(response=403, ref="#/components/responses/403"),
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/seg/usuarios/{iCredId}/perfiles",
     *     tags={"Gestión de usuarios"},
     *     summary="Agrega un perfil al usuario especificado. Los parámetros dependen de la opción seleccionada. Se requiere rol de administrador.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(ref="#/components/parameters/iCredEntPerfId"),
     *     @OA\Parameter(
     *         name="opcionBusquedaSeleccionada",
     *         in="query",
     *         required=true,
     *         description="Opción de perfil a agregar",
     *         @OA\Schema(type="string", enum={"dremo", "ugel", "iiee"})
     *     ),
     *     @OA\Parameter(
     *         name="iCredId",
     *         in="path",
     *         required=true,
     *         description="Id de la credencial del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="iEntId",
     *         in="query",
     *         required=true,
     *         description="Id de la entidad donde labora el usuario",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="iPerfilId",
     *         in="query",
     *         required=true,
     *         description="Id del perfil a asignar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="iCursosNivelGradId",
     *         in="query",
     *         required=false,
     *         description="Id del curso del usuario (para opción DREMO, UGEL)",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="iUgelId",
     *         in="query",
     *         required=false,
     *         description="Id de la UGEL donde labora el usuario (para opción UGEL)",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="iSedeId",
     *         in="query",
     *         required=false,
     *         description="Id de la sede de la institución educativa (para opción Sede)",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Se ha asignado el perfil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Se ha asignado el perfil"),
     *         )
     *     ),
     *
     *     @OA\Response(response=400, ref="#/components/responses/400"),
     *     @OA\Response(response=403, ref="#/components/responses/403"),
     * )
     */
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
