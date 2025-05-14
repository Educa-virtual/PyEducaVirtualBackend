<?php

namespace App\Http\Controllers\seg;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Models\seg\Usuario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
            //Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            switch ($request->get('opcionBusquedaSeleccionada')) {
                case 'documento':
                    $documento = $request->get('criterioBusqueda', NULL);
                    $apellidos = null;
                    $nombres = null;
                    break;
                case 'apellidos':
                    $documento = null;
                    $apellidos = $request->get('criterioBusqueda', NULL);
                    $nombres = null;
                    break;
                case 'nombres':
                    $documento = null;
                    $apellidos = null;
                    $nombres = $request->get('criterioBusqueda', NULL);
                    break;
                default:
                    $documento = null;
                    $apellidos = null;
                    $nombres = null;
                    break;
            }
            $parametros = [
                0,
                $request->get('offset', 0),
                $request->get('limit', 20),
                $documento,
                $apellidos,
                $nombres,
                $request->get('filtroInstitucionSeleccionada', NULL),
                $request->get('filtroPerfilSeleccionado', NULL)
            ];
            $dataUsuarios = Usuario::selUsuariosPerfiles($parametros);
            $total = 20; //Usuario::selUsuariosPerfiles($parametros);
            $resultado = [
                'total' => 20, //$total[0]->totalFilas,
                'data' => $dataUsuarios
            ];
            //$total = Customer::count();
            //$customers = Customer::skip($skip)->take($limit)->get();
            return FormatearMensajeHelper::ok('Datos obtenidos', $resultado, Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function obtenerPerfilesUsuario($iCredId)
    {
        try {
            //Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            $parametros = [
                'json' => '{\"id\":\"1289\"}',
                '_opcion' => 'getPerfilesUsuario',
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
            //Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            /*$usuario = Usuario::find($request->get('iUsuarioId'));
            if ($usuario) {
                $usuario->estado = $request->get('estado');
                $usuario->save();
                return FormatearMensajeHelper::ok('Estado del usuario actualizado', null, Response::HTTP_OK);
            } else {
                return FormatearMensajeHelper::error('Usuario no encontrado', null, Response::HTTP_NOT_FOUND);
            }*/
            $parametros = [
                $iCredId,
                $request->iCredEstado,
                1
            ];
            Usuario::updiCredEstadoCredencialesXiCredId($parametros);
            $mensaje = $request->iCredEstado == 1 ? 'activado' : 'desactivado';
            return FormatearMensajeHelper::ok('El usuario ha sido ' . $mensaje, null, Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
