<?php

namespace App\Models\asi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AsistenciaGeneral extends Model
{
    protected $table = 'asi.asistencia_general';
    protected $primaryKey = 'idAsistencia';
    public $timestamps = false;

    public static function selCantidadRegistrosPorTipo($iEstudianteId, $iYAcadId, $iSedeId, $iTipoAsiId, $desde, $hasta)
    {
        return DB::selectOne("SELECT COUNT(idAsistencia) AS cantidad
FROM asi.asistencia_general WHERE iEstudianteId=? AND iYAcadId=? AND iSedeId=?
AND iTipoAsiId=? AND CAST(dtAsistencia AS DATE) BETWEEN ? AND ?", [$iEstudianteId, $iYAcadId, $iSedeId, $iTipoAsiId, $desde, $hasta]);
    }

    public static function selEstudiantesConFalta($fecha, $matriculas)
    {
        // Convertir todos los valores a enteros para mayor seguridad
        $matriculas = array_map('intval', $matriculas);
        $placeholders = implode(',', array_fill(0, count($matriculas), '?'));
        $sql = "SELECT asi.idAsistencia,asi.iMatrId, persEst.cPersNombre AS cPersNombreEst, persEst.cPersPaterno AS cPersPaternoEst,
        persEst.cPersMaterno AS cPersMaternoEst,
        persApo.cPersNombre AS cPersNombreApo, persApo.cPersPaterno AS cPersPaternoApo, persApo.cPersMaterno AS  cPersMaternoApo,
        persApo.cPersCorreo, ie.cIieeNombre
        FROM asi.asistencia_general AS asi
        INNER JOIN apo.apoderado AS apo ON apo.iEstudianteId=asi.iEstudianteId
        INNER JOIN acad.estudiantes AS est ON est.iEstudianteId=asi.iEstudianteId
        INNER JOIN grl.personas AS persEst ON persEst.iPersId=est.iPersId
        INNER JOIN grl.personas AS persApo ON persApo.iPersId=apo.iPersId
        INNER JOIN acad.matricula AS mat ON mat.iMatrId=asi.iMatrId
        INNER JOIN acad.sedes AS sede ON sede.iSedeId=mat.iSedeId
        INNER JOIN acad.institucion_educativas AS ie ON ie.iIieeId=sede.iIieeId
        WHERE CAST(dtAsistencia AS DATE)=? AND (bNotificado IS NULL OR bNotificado=0) AND asi.iMatrId IN ($placeholders)";
        return DB::select($sql, array_merge([$fecha], $matriculas));
    }
}
