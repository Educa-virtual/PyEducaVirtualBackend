<?php

namespace App\Http\Controllers\seg;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Models\seg\Usuario;
use App\Services\seg\UsuariosService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UsuarioController
{
    /**
     * @OA\Post(
     *     path="/api/seg/usuarios/perfiles",
     *     tags={"Gestión de usuarios"},
     *     summary="Obtiene la lista de usuarios y sus perfiles.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(ref="#/components/parameters/iCredEntPerfId"),
     *     @OA\Parameter(
     *         name="iSugerenciaId",
     *         in="path",
     *         required=true,
     *         description="Id de la sugerencia",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sugerencia registrada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Se ha registrado su sugerencia"),
     *             @OA\Property(property="data", type="int", example="ID de la sugerencia registrada")
     *         )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/400"),
     *     @OA\Response(response=403, ref="#/components/responses/403"),
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     * )
     */
    function obtenerListaUsuariosPerfiles(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            $parametros = UsuariosService::generarParametrosParaObtenerUsuarios('data', $request);
            $dataUsuarios = Usuario::selUsuariosPerfiles($parametros);
            $parametros = UsuariosService::generarParametrosParaObtenerUsuarios('cantidad', $request);
            $dataCantidad = Usuario::selUsuariosPerfiles($parametros);
            $resultado = [
                'totalFilas' => $dataCantidad[0]->totalFilas,
                'dataUsuarios' => $dataUsuarios,
                'fechaServidor' => new Carbon()
            ];
            return FormatearMensajeHelper::ok('Datos obtenidos', $resultado, Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function obtenerPerfilesUsuario($iCredId)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            $iPersId = Usuario::obtenerIdPersonaPorIdCred($iCredId);
            $parametros = [
                '{"id":' . $iPersId . '}',
                'getPerfilesUsuario'
            ];
            $data = Usuario::selPerfilesUsuario($parametros);
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
            Usuario::updiCredEstadoCredencialesXiCredId($parametros);
            $mensaje = $request->iCredEstado == 1 ? 'activado' : 'desactivado';
            return FormatearMensajeHelper::ok('El usuario ha sido ' . $mensaje, null, Response::HTTP_OK);
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
            Usuario::updReseteoClaveCredencialesXiCredId($parametros);
            return FormatearMensajeHelper::ok('La contraseña del usuario ha sido restablecida. Ahora es su usuario.', null, Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function eliminarPerfilUsuario($iCredId, $iCredEntPerfId)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            $parametros = [
                $iCredEntPerfId
            ];
            Usuario::delCredencialesEentidadesPperfiles($parametros);
            return FormatearMensajeHelper::ok('El perfil del usuario ha sido eliminado', null, Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
