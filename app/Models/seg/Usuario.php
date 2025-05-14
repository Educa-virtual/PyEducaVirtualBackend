<?php

namespace App\Models\seg;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Usuario extends Model
{
    public static function selUsuariosPerfiles($parametros)
    {
        return DB::select("EXEC [seg].[SP_SEL_usuariosPerfiles] @soloTotal=?, @offset=?, @limit=?,
        @documentoFiltro=?, @apellidosFiltro=?, @nombresFiltro=?,@institucionFiltro=?,@rolFiltro=?", $parametros);
    }

    public static function updiCredEstadoCredencialesXiCredId($parametros)
    {
        return DB::select("EXEC [seg].[Sp_UPD_iCredEstado_credencialesXiCredId] @_iCredId=?, @_iCredEstado=?, @_iCredSesionId=?", $parametros);
    }

    public static function selPerfilesUsuario($parametros)
    {
        return DB::select("EXEC acad.SP_SEL_stepCalendarioAcademicoDesdeJsonOpcion ?,?",$parametros);
    }
}
