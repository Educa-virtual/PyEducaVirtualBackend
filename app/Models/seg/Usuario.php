<?php

namespace App\Models\seg;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Usuario extends Model
{
    public static function obtenerIdPersonaPorIdCred($iCredId)
    {
        $data = DB::selectOne("SELECT TOP 1 iPersId FROM seg.credenciales WHERE iCredId=?", [$iCredId]);
        return $data->iPersId ?? null;
    }

    public static function selUsuariosPerfiles($parametros)
    {
        return DB::select("EXEC [seg].[SP_SEL_usuariosPerfiles] @soloTotal=?, @offset=?, @limit=?,
        @documentoFiltro=?, @apellidosFiltro=?, @nombresFiltro=?, @iPersId=NULL", $parametros); //,@institucionFiltro=?,@rolFiltro=?
    }

    public static function updFechaVigenciaCuenta($iCredId, $dtCredCaduca)
    {
        return DB::statement("UPDATE seg.credenciales SET dtCredCaduca=? WHERE iCredId=?", [$dtCredCaduca, $iCredId]);
    }

    public static function updiCredEstadoCredencialesXiCredId($parametros)
    {
        return DB::select("EXEC [seg].[Sp_UPD_iCredEstado_credencialesXiCredId] @_iCredId=?, @_iCredEstado=?, @_iCredSesionId=?", $parametros);
    }

    public static function selPerfilesUsuario($parametros)
    {
        return DB::select("EXEC acad.SP_SEL_stepCalendarioAcademicoDesdeJsonOpcion ?,?",$parametros);
    }

    public static function updReseteoClaveCredencialesXiCredId($parametros) {
        return DB::select("EXEC [seg].[Sp_UPD_ReseteoClave_credencialesXiCredId] @_iCredId=?, @_iCredSesionId=?", $parametros);
    }

    public static function delCredencialesEentidadesPperfiles($parametros)
    {
        return DB::select("EXEC [seg].[Sp_DEL_credenciales_entidades_perfiles] @_iCredEntPerfId=? ", $parametros);
    }

    public static function agregarPerfil($iCredId, $request) {
        $iPersId = self::obtenerIdPersonaPorIdCred($iCredId);
        //file_put_contents('D:/perfil.txt', $iPersId);
        $json = [
            'iEntId' => $request->iEntId,
            'iPerfilId' => $request->iPerfilId,
            'iCursosNivelGradId' => $request->iCursosNivelGradId,
            'cTipo'=> $request->cTipo,
            'iUgelId' => $request->iUgelId,
            'iSedeId' => $request->iSedeId,
            'iPersId' => $iPersId,
        ];
        $solicitud = [
            json_encode($json),
            $request->opcion
        ];
        //file_put_contents('D:/perfil.txt', $solicitud);
        $query = DB::select("EXEC acad.SP_INS_stepCalendarioAcademicoDesdeJsonOpcion ?,?", $solicitud);
        return $query[0]->resultado;
    }

    public static function registrarUsuario($request, $iCredId) {
        // Validar los datos de entrada
            $request->validate([
                'data' => 'required|array',
                'data.cPersSexo' => 'required',
                'data.cPersNombre' => 'required'
            ]);
            $item = $request->data;
            $iPersId = null;
            $mensaje = '';

            if (empty($item["iPersId"])) {
                $iTipoPersId = ((int)$item['iTipoIdentId'] == 2) ? 2 : 1;
                $parametros = [
                    isset($iTipoPersId) ? $iTipoPersId : null,
                    $item['iTipoIdentId'],
                    $item['cPersDocumento'],
                    $item['cPersPaterno'],
                    isset($item['cPersMaterno']) ? $item['cPersMaterno'] : null,
                    $item['cPersNombre'],
                    $item['cPersSexo'],
                    isset($item['cPersCorreo']) ? $item['cPersCorreo'] : null,
                    isset($item['cPersCelular']) ? $item['cPersCelular'] : null,
                    isset($item['cPersFotografia']) ? (trim($item['cPersFotografia']) ?: null) : null,
                    isset($item['cPersTelefono']) ? $item['cPersTelefono'] : null,
                    isset($item['cPersDireccion']) ? $item['cPersDireccion'] : null,
                    isset($item['cPersReferencia']) ? $item['cPersReferencia'] : null,
                    isset($item['cPersDomicilio']) ? (trim($item['cPersDomicilio']) ?: null) : null,
                    isset($iCredId) ? $iCredId : null,
                    isset($item['iNacionId']) ? $item['iNacionId'] : null,
                    isset($item['iPaisId']) ? (trim($item['iPaisId']) ?: null) : null,
                    isset($item['iDptoId']) ? (trim($item['iDptoId']) ?: null) : null,
                    isset($item['iPrvnId']) ? (trim($item['iPrvnId']) ?: null) : null,
                    isset($item['iDsttId']) ? (trim($item['iDsttId']) ?: null) : null,
                ];

                $data = DB::select('execute grl.Sp_INS_personas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                $iPersId = !empty($data) ? $data[0]->iPersId : null;

                if ($iPersId) {
                    DB::select('execute seg.Sp_INS_credenciales ?,?,?', [10, $iPersId, $iCredId]);
                    $mensaje = 'Se ha registrado el usuario';
                } else {
                    throw new Exception('Error al registrar el personal');
                }
            } else {
                $iPersId = $item["iPersId"];
                $mensaje = 'El usuario ya se encuentra registrado';
            }
            $persona = DB::select('EXEC [seg].[SP_SEL_usuariosPerfiles] @iPersId=?', [$iPersId]);
            return [
                'data' => $persona[0],
                'mensaje' => $mensaje
            ];
    }
}
