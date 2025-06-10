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

    public static function selUsuarios($parametros)
    {
        return DB::select("EXEC [seg].[SP_SEL_usuarios] @soloTotal=?, @offset=?, @limit=?,
        @documentoFiltro=?, @apellidosFiltro=?, @nombresFiltro=?, @iPersId=NULL", $parametros);
    }

    public static function selUsuarioPorIdPersona($iPersId)
    {
         return DB::selectOne('EXEC [seg].[SP_SEL_usuarios] @iPersId=?', [$iPersId]);
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

    public static function insPersonas($parametros)
    {
        return DB::select('execute grl.Sp_INS_personas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
    }

    public static function insCredenciales($iPersId, $iCredId)
    {
        DB::statement('execute seg.Sp_INS_credenciales ?,?,?', [10, $iPersId, $iCredId]);
    }
}
