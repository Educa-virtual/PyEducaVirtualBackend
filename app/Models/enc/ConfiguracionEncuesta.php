<?php

namespace App\Models\enc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ConfiguracionEncuesta extends Model
{
    public static function insConfiguracionEncuesta($params)
    {
        $resultado = DB::selectOne("EXEC [enc].[Sp_INS_configuracionEncuesta]
        @cConfEncNombre = ?,
        @cConfEncSubNombre = ?,
        @cConfEncDesc = ?,
        @dConfEncInicio = ?,
        @dConfEncFin = ?,
        @iTiemDurId = ?,
        @iCategoriaEncuestaId = ?,
        @iCredIdCreador = ?,
        @iYAcadId = ?,
        @bDirigidoDirectores = ?,
        @bDirigidoDocentes = ?,
        @bDirigidoEstudiantes = ?,
        @bDirigidoApoderados = ?,
        @bDirigidoEspDremo = ?,
        @bDirigidoEspUgel = ?", $params);
        return $resultado->iConfEncId;
    }
}
