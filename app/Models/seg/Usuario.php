<?php

namespace App\Models\seg;

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
}
