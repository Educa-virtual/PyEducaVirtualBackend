<?php

namespace App\Models\asi;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ControlAsistencia extends Model
{
    protected $table = 'asi.control_asistencias';
    protected $primaryKey = 'iCtrlAsistenciaId';
    public $timestamps = false;

    public static function selEstudiantesConFalta($solicitud, $fecha)
    {
        return DB::select("SELECT asi.iCtrlAsistenciaId,persEst.cPersNombre AS cPersNombreEst, persEst.cPersPaterno AS cPersPaternoEst,
persEst.cPersMaterno AS cPersMaternoEst,
persApo.cPersNombre AS cPersNombreApo, persApo.cPersPaterno AS cPersPaternoApo, persApo.cPersMaterno AS  cPersMaternoApo,
persApo.cPersCorreo, ie.cIieeNombre, cur.cCursoNombre
FROM asi.control_asistencias AS asi
INNER JOIN apo.apoderado AS apo ON apo.iEstudianteId=asi.iEstudianteId
INNER JOIN acad.estudiantes AS est ON est.iEstudianteId=asi.iEstudianteId
INNER JOIN grl.personas AS persEst ON persEst.iPersId=est.iPersId
INNER JOIN grl.personas AS persApo ON persApo.iPersId=apo.iPersId
INNER JOIN acad.sedes AS sede ON sede.iSedeId=asi.iSedeId
INNER JOIN acad.institucion_educativas AS ie ON ie.iIieeId=sede.iIieeId
INNER JOIN acad.cursos AS cur ON cur.iCursoId=asi.iCursoId
WHERE asi.iCursoId=? AND iYAcadId=?
AND iSeccionId=? AND idDocCursoId=? AND asi.iSedeId=? AND CAST(dtCtrlAsistencia AS DATE)=? AND iTipoAsiId=3
AND (bNotificado IS NULL OR bNotificado=0)", [
            $solicitud[1],
            $solicitud[5],
            $solicitud[4],
            $solicitud[9],
            $solicitud[10],
            $fecha->format('Ymd')
        ]);
    }
}
