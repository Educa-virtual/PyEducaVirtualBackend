<?php

namespace App\Models\seg;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Usuario extends Model
{
    public static function insCredencial(Object $request)
    {
        $params = [
            $request->header('iCredEntPerfId'),
            $request->iPersId,
            $request->cCredUsuario,
            $request->password,
            $request->cCredToken,
            $request->dtCredCaduca,
            $request->cCredTokenPassword,
        ];
        $placeholders = implode(',', array_fill(0, count($params), '?'));
        return DB::selectOne("EXEC seg.Sp_INS_credencial $placeholders", $params);
    }

    public static function updCredencial(Object $request)
    {
        $params = [
            $request->header('iCredEntPerfId'),
            $request->iCredId,
            $request->cCredUsuario,
            $request->password,
            $request->cCredToken,
            $request->iCredIntentos,
            $request->dtCredCaduca,
            $request->cCredTokenPassword,
        ];
        $placeholders = implode(',', array_fill(0, count($params), '?'));
        return DB::selectOne("EXEC seg.Sp_UPD_credencial $placeholders", $params);
    }

    public static function insPerfil(Object $request)
    {
        $params = [
            $request->header('iCredEntPerfId'),
            $request->iCredId,
            $request->iPerfilId,
            $request->iSedeId,
        ];
        $placeholders = implode(',', array_fill(0, count($params), '?'));
        return DB::selectOne("EXEC seg.Sp_INS_Perfil $placeholders", $params);
    }

    public static function obtenerIdPersonaPorIdCred($iCredId)
    {
        $data = DB::selectOne("SELECT TOP 1 iPersId FROM seg.credenciales WHERE iCredId=?", [$iCredId]);
        return $data->iPersId ?? null;
    }

    public static function selUsuarios($parametros)
    {
        return DB::select("EXEC [seg].[SP_SEL_usuarios] @soloTotal=?, @offset=?,  @limit=?, @opcionBusqueda=?,
        @criterioBusqueda=?, @institucionSeleccionada=?, @perfilSeleccionado=?, @iUgelSeleccionada=?, @ieSedeSeleccionada=?,
        @iPersId=NULL", $parametros);
    }

    public static function selUsuarioPorIdPersona($iPersId)
    {
        return DB::selectOne('EXEC [seg].[SP_SEL_usuarios] @iPersId=?', [$iPersId]);
    }

    public static function selUsuarioPorCredencial($cCredUsuario)
    {
        return DB::selectOne("SELECT TOP 1 per.iPersId, cred.iCredId,cPersCorreo, per.cPersNombre, per.cPersPaterno, per.cPersMaterno
        FROM grl.personas AS per
        INNER JOIN seg.credenciales AS cred ON cred.iPersId=per.iPersId
        WHERE cred.cCredUsuario=?", [$cCredUsuario]);
    }

    public static function updFechaVigenciaCuenta($iCredId, $dtCredCaduca)
    {
        return DB::statement("UPDATE seg.credenciales SET dtCredCaduca=? WHERE iCredId=?", [$dtCredCaduca, $iCredId]);
    }

    public static function updiCredEstadoCredencialesXiCredId($parametros)
    {
        return DB::statement("EXEC [seg].[Sp_UPD_iCredEstado_credencialesXiCredId] @_iCredId=?, @_iCredEstado=?, @_iCredSesionId=?", $parametros);
    }

    public static function selPerfilesUsuario($iCredId)
    {
        return DB::select("EXEC [seg].[SP_SEL_PerfilesUsuario] @iCredId=?", [$iCredId]);
    }

    public static function updReseteoClaveCredencialesXiCredId($parametros)
    {
        return DB::statement("EXEC [seg].[Sp_UPD_ReseteoClave_credencialesXiCredId] @_iCredId=?, @_iCredSesionId=?", $parametros);
    }

    public static function delCredencialesEntidadesPerfiles($iCredId, $iCredEntPerfId)
    {
        return DB::statement("EXEC [seg].[Sp_DEL_credenciales_entidades_perfiles] @_iCredEntPerfId=?", [$iCredEntPerfId]);
    }

    public static function insPerfilDremo($iCredId, $request)
    {
        $cTipo = $request->iPerfilId == 2 ? 'EspecialistaDremo' : 'PerfilModuloDremo';
        DB::statement("EXEC [seg].[SP_INS_PerfilDremo] @iEntId=?, @iPerfilId=?, @iCursosNivelGradId=?, @iCredId=?, @cTipo=?", [
            $request->iEntId,
            $request->iPerfilId,
            $request->iCursosNivelGradId,
            $iCredId,
            $cTipo
        ]);
    }

    public static function insPerfilUgel($iCredId, $request)
    {
        $params = [
            $request->iUgelId,
            $request->iEntId,
            $request->iPerfilId,
            $iCredId,
            $request->iCursosNivelGradId
        ];
        DB::statement("EXEC [seg].[SP_INS_PerfilUgel] @iUgelId=?, @iEntId=?, @iPerfilId=?, @iCredId=?, @iCursosNivelGradId=?", [
            $request->iUgelId,
            $request->iEntId,
            $request->iPerfilId,
            $iCredId,
            $request->iCursosNivelGradId
        ]);
    }

    public static function insPerfilIiee($iCredId, $request)
    {
        DB::statement("EXEC [seg].[SP_INS_PerfilIiee] @iSedeId=?, @iEntId=?, @iPerfilId=?, @iCredId=?", [
            $request->iSedeId,
            $request->iEntId,
            $request->iPerfilId,
            $iCredId
        ]);
    }

    public static function insCredenciales($iPersId, $iCredId)
    {
        DB::statement('execute seg.Sp_INS_credenciales ?,?,?', [10, $iPersId, $iCredId]);
    }

    public static function updCredenciasUpdatePassword($parametros)
    {
        DB::statement("execute seg.Sp_UPD_credenciasxUpdatePassword @_iCredId=?, @_iPersId=?, @_contraseniaActual=?, @_contraseniaNueva=?", $parametros);
    }

    public static function selDetallesCredencialEntidad($iCredEntPerfId)
    {
        return DB::selectOne("SELECT cep.*, ce.*,c.iPersId FROM seg.credenciales_entidades_perfiles AS cep
INNER JOIN seg.credenciales_entidades AS ce ON ce.iCredEntId=cep.iCredEntId
INNER JOIN seg.credenciales AS c ON c.iCredId=ce.iCredId
WHERE iCredEntPerfId=?", [$iCredEntPerfId]);
    }
}
