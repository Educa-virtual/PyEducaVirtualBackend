<?php

namespace App\Http\Controllers\seg;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Models\seg\Usuario;
use App\Models\User;
use App\Services\seg\UsuariosService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Format;

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

    public function actualizarFechaVigenciaUsuario($iCredId, Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            Usuario::updFechaVigenciaCuenta($iCredId, $request->dtCredCaduca);
            return FormatearMensajeHelper::ok('Se ha actualizado la fecha de vigencia de la cuenta', null, Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function registrarUsuario(Request $request)
    {
        try {
            // Validar los datos de entrada
            $request->validate([
                'data' => 'required|array',
                'iSedeId' => 'required|integer',
                'iYAcadId' => 'required|integer',
                //'iCredId' => 'required|integer',
                //'condicion' => 'required|string',
            ]);

            $item = $request->data;
            //$iSedeId = $request->iSedeId;
            //$iYAcadId = $request->iYAcadId;
            $iCredId = Auth::user()->iCredId;
            //$condicion = $request->condicion;
            $iPersId = null;
            $mensaje = '';
            //$procesados = [];
            //$observados = [];

            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR]]);
            // Registrar nuevo personal si no existe
            if (empty($item["iPersId"])) {
                $iTipoPersId = ((int)$item['iTipoIdentId'] == 2) ? 2 : 1;
                $parametros = [
                    $iTipoPersId,
                    $item['iTipoIdentId'],
                    $item['cPersDocumento'],
                    $item['cPersPaterno'],
                    $item['cPersMaterno'],
                    $item['cPersNombre'],
                    trim($item['cPersSexo']) ?: 'M',
                    null,
                    NULL,
                    trim($item['cPersFotografia']) ?: NULL,
                    NULL,
                    NULL,
                    NULL,
                    trim($item['cPersDomicilio']) ?: NULL,
                    $iCredId,
                    $item['iNacionId'],
                    trim($item['iPaisId']) ?: NULL,
                    trim($item['iDptoId']) ?: NULL,
                    trim($item['iPrvnId']) ?: NULL,
                    trim($item['iDsttId']) ?: NULL,
                ];

                $data = DB::select('execute grl.Sp_INS_personas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                $iPersId = !empty($data) ? $data[0]->iPersId : null;

                if ($iPersId) {
                    DB::select('execute seg.Sp_INS_credenciales ?,?,?', [10, $iPersId, $iCredId]);
                    $mensaje = 'Se ha registrado el personal';
                    //return FormatearMensajeHelper::ok('Se ha registrado el personal.', ['iPersId' => $iPersId], Response::HTTP_OK);
                    //$procesados[] = ['validated' => true, 'message' => 'Nuevo personal registrado y credencial generada.', 'data' => $data, 'item' => $item];
                } else {
                    throw new Exception('Error al registrar el personal');
                    //$observados[] = ['validated' => false, 'message' => 'Error al registrar el personal.', 'item' => $item];
                }
            } else {
                $iPersId = $item["iPersId"];
                $mensaje = 'El personal ya se encuentra registrado';
                //$usuario = null; //User::find($iCredId);
                //return FormatearMensajeHelper::ok('El personal ya se encuentra registrado.', ['iPersId' => $iPersId], Response::HTTP_OK);
            }
            $persona = DB::select('EXEC [seg].[SP_SEL_usuariosPerfiles] @iPersId=?', [$iPersId]);
            return FormatearMensajeHelper::ok($mensaje, $persona[0]);
            // Procesar según la condición
            /*if ($condicion === 'add_personal_ie') {
                if (is_null($iPersId) || is_null($item["iPersCargoId"]) || is_null($item["iYAcadId"]) || is_null($item["iSedeId"])) {
                    $observados[] = ['validated' => false, 'message' => 'Faltan parámetros requeridos.', 'item' => $item];
                } else {
                    $iPersCargoId = $item["iPersCargoId"];
                    $iHorasLabora = $item["iHorasLabora"] ?? 0;
                    $iPerfilId = $perfilMapping[$iPersCargoId] ?? 0;

                    $id = DB::table('acad.personal_ies')
                        ->where('iPersId', $iPersId)
                        ->where('iSedeId', $iSedeId)
                        ->where('iYAcadId', $iYAcadId)
                        ->value('id');

                    if ($id) {
                        $procesados[] = ['validated' => false, 'message' => 'Ya existe registro en Personal IE.', 'item' => $item];
                    } else {
                        $id = DB::table('acad.personal_ies')->insertGetId([
                            'iPersId' => $iPersId,
                            'iPersCargoId' => $iPersCargoId,
                            'iHorasLabora' => $iHorasLabora,
                            'iYAcadId' => $iYAcadId,
                            'iSedeId' => $iSedeId,
                        ]);
                        $procesados[] = ['validated' => true, 'message' => 'Se generó registro en Personal IE.', 'item' => $item];
                    }

                    $data = DB::select('execute seg.Sp_INS_credenciales_IE ?,?,?,?,?', [10, $iPersId, $iCredId, $iSedeId, $iPerfilId]);
                    $procesados[] = ['validated' => true, 'message' => 'Credencial generada.', 'data' => $data, 'item' => $item];
                }
            }*/

            /*if ($condicion === 'add_credencial_ie') {
                $iPerfilId = $request->iPerfilId;

                if (is_null($iPersId) || is_null($iCredId) || is_null($iPerfilId)) {
                    $observados[] = ['validated' => false, 'message' => 'Faltan parámetros requeridos.', 'item' => $item];
                } else {

                    $data = DB::select('execute seg.Sp_INS_credenciales_IE ?,?,?,?,?', [10, $iPersId, $iCredId, $iSedeId, $iPerfilId]);
                    $procesados[] = ['validated' => true, 'message' => 'Credencial generada.', 'data' => $data, 'item' => $item];
                }
            }*/
            //if ($condicion === 'add_credencial') {

            /*if (is_null($iPersId) || is_null($iCredId)) {
                $observados[] = ['validated' => false, 'message' => 'Faltan parámetros requeridos.', 'item' => $item];
            } else {

                $data = DB::select('execute seg.Sp_INS_credenciales ?,?,?', [10, $iPersId, $iCredId]);
                $procesados[] = ['validated' => true, 'message' => 'Credencial generada.', 'data' => $data, 'item' => $item];
            }*/
            //}
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
            //$observados[] = ['validated' => false, 'message' => 'Error en base de datos: ' . $e->getMessage(), 'item' => $item];
        }

        // Construir la respuesta
        /*$response = [
            'procesados' => $procesados,
            'observados' => $observados,
        ];

        $estado = (count($observados) > 0) ? 500 : 201;
        return new JsonResponse($response, $estado);*/
    }
}
