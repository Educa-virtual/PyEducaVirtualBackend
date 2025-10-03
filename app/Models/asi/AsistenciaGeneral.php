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

    public static function selAsistenciaEstudiantePorPeriodo($iMatrId, $anio, $mes) {
        return DB::select("SELECT asi.idAsistencia, CAST(dtAsistencia AS DATE) AS dtAsistencia, tipo.iTipoAsiId, tipo.cTipoAsiLetra,
        tipo.cTipoAsiNombre
FROM asi.asistencia_general AS asi
INNER JOIN asi.tipo_asistencias AS tipo ON asi.iTipoAsiId=tipo.iTipoAsiId
WHERE asi.iMatrId=? AND YEAR(dtAsistencia)=? AND MONTH(dtAsistencia)=?
ORDER BY dtAsistencia ASC", [$iMatrId, $anio, $mes]);
    }
}
