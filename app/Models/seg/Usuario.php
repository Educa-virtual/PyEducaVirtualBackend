<?php

namespace App\Models\seg;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Usuario extends Model
{
    public static function selCredencialParametros(Object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC seg.Sp_SEL_credencialParametros $placeholders", $parametros);
    }

    public static function obtenerIdPersonaPorIdCred($iCredId)
    {
        $data = DB::selectOne("SELECT TOP 1 iPersId FROM seg.credenciales WHERE iCredId=?", [$iCredId]);
        return $data->iPersId ?? null;
    }

    public static function selUsuarios(Object $request)
    {
        $parametros = [
            $request->soloTotal,
            $request->offset,
            $request->limit,
            $request->opcionBusqueda,
            $request->criterioBusqueda,
            $request->institucionSeleccionada,
            $request->perfilSeleccionado,
            $request->iUgelSeleccionada,
            $request->ieSedeSeleccionada,
            $request->iPersId,
            $request->nivelSeleccionado,
            $request->estadoSeleccionado,
            $request->fechaDesde,
            $request->fechaHasta,
            $request->columnaOrdenar,
            $request->direccionOrdenar,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC seg.SP_SEL_usuarios $placeholders", $parametros);
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

    public static function updFechaVigenciaCuenta(Object $datos)
    {
        $parametros = [
            $datos->iCredId,
            $datos->dtCredCaduca,
            $datos->iCredEntPerfId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::statement("EXEC seg.Sp_UPD_credencialVigencia $placeholders", $parametros);
    }

    public static function updCredencialEstado(Object $datos)
    {
        $parametros = [
            $datos->iCredId,
            $datos->iCredEstado,
            $datos->iCredEntPerfId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::statement("EXEC seg.Sp_UPD_credencialEstado $placeholders", $parametros);
    }

    public static function selPerfilesUsuario($iCredId)
    {
        $parametros = [
            $iCredId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC seg.SP_SEL_PerfilesUsuario $placeholders", $parametros);
    }

    public static function updReseteoClaveCredencialesXiCredId($parametros)
    {
        return DB::statement("EXEC [seg].[Sp_UPD_ReseteoClave_credencialesXiCredId] @_iCredId=?, @_iCredSesionId=?", $parametros);
    }

    public static function delCredencialesEntidadesPerfiles($iCredId, $iCredEntPerfId)
    {
        return DB::statement("EXEC [seg].[Sp_DEL_credenciales_entidades_perfiles] @_iCredEntPerfId=?", [$iCredEntPerfId]);
    }

    public static function insPerfil($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iCredId,
            $request->iPerfilId,
            $request->iSedeId,
            $request->iUgelId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC seg.Sp_INS_perfil $placeholders", $parametros);
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

    public static function insPersonas($datos)
    {
        $parametros = [
            $datos->iTipoPersId ?? NULL,
            $datos->iTipoIdentId ?? NULL,
            $datos->cPersDocumento ?? NULL,
            $datos->cPersPaterno ?? NULL,
            $datos->cPersMaterno ?? NULL,
            $datos->cPersNombre ?? NULL,
            $datos->cPersSexo ?? NULL,
            $datos->dPersNacimiento ?? NULL,
            $datos->iTipoEstCivId ?? NULL,
            $datos->cPersFotografia ?? NULL,
            $datos->cPersRazonSocialNombre ?? NULL,
            $datos->cPersRazonSocialCorto ?? NULL,
            $datos->cPersRazonSocialSigla ?? NULL,
            $datos->cPersDomicilio ?? NULL,
            $datos->iCredSesionId ?? NULL,
            $datos->iNacionId ?? NULL,
            $datos->iPaisId ?? NULL,
            $datos->iDptoId ?? NULL,
            $datos->iPrvnId ?? NULL,
            $datos->iDsttId ?? NULL,
            $datos->cPersTelefono ?? NULL,
            $datos->cPersCorreo ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("execute grl.Sp_INS_personas $placeholders", $parametros);
    }

    public static function updPersonas($datos)
    {
        $parametros = [
            $datos->iPersId,
            $datos->cPersDocumento,
            $datos->cPersPaterno,
            $datos->cPersMaterno,
            $datos->cPersNombre,
            $datos->cPersSexo,
            $datos->dPersNacimiento,
            $datos->iTipoEstCivId,
            $datos->cPersFotografia,
            $datos->cPersRazonSocialNombre,
            $datos->cPersRazonSocialCorto,
            $datos->cPersRazonSocialSigla,
            $datos->cPersDomicilio,
            $datos->iCredSesionId,
            $datos->iPersRepresentanteLegalId,
            $datos->iNacionId,
            $datos->iPaisId,
            $datos->iDptoId,
            $datos->iPrvnId,
            $datos->iDsttId,
            $datos->cPersTelefono,
            $datos->cPersCorreo,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("execute grl.Sp_UPD_personas $placeholders", $parametros);
    }

    public static function insCredenciales($data)
    {
        $parametros = [
            $data->iEntId,
            $data->iPersId,
            $data->iCredEntPerfId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        DB::statement("execute seg.Sp_INS_credenciales $placeholders", $parametros);
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

    public static function updPerfilEstado(Object $datos)
    {
        $parametros = [
            $datos->iCredEntPerfId,
            $datos->iCredEntPerfEstado,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC seg.Sp_UPD_perfilEstado $placeholders", $parametros);
    }
}
