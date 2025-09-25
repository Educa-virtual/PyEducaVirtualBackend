<?php

namespace App\Models\asi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AsistenciaGeneral extends Model
{
    public static function selCantidadRegistrosPorTipo($iEstudianteId, $iYAcadId, $iSedeId, $iTipoAsiId, $desde, $hasta)
    {
        return DB::selectOne("SELECT COUNT(idAsistencia) AS cantidad
FROM asi.asistencia_general WHERE iEstudianteId=? AND iYAcadId=? AND iSedeId=?
AND iTipoAsiId=? AND CAST(dtAsistencia AS DATE) BETWEEN ? AND ?", [$iEstudianteId, $iYAcadId, $iSedeId, $iTipoAsiId, $desde, $hasta]);
    }
}
