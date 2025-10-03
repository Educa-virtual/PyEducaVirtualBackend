<?php

namespace App\Models\asi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AsistenciaControl extends Model
{
    public static function selAsistenciaEstudiantePorFecha($matricula, $fecha)
    {
        return DB::select(
            "SELECT cur.cCursoNombre, tasi.cTipoAsiNombre,tasi.cTipoAsiLetra,
per.cPersPaterno, per.cPersMaterno,per.cPersNombre
FROM asi.control_asistencias AS casi
INNER JOIN asi.tipo_asistencias AS tasi ON tasi.iTipoAsiId=casi.iTipoAsiId
INNER JOIN acad.docente_cursos AS docur ON casi.idDocCursoId=docur.idDocCursoId
INNER JOIN acad.docentes AS doc ON doc.iDocenteId=docur.iDocenteId
INNER JOIN grl.personas AS per ON per.iPersId=doc.iPersId
INNER JOIN acad.cursos AS cur ON cur.iCursoId=casi.iCursoId
WHERE iEstudianteId=? AND docur.iYAcadId=? AND casi.iSedeId=? AND CAST(dtCtrlAsistencia AS DATE)=?
ORDER BY cur.cCursoNombre",
            [$matricula->iEstudianteId, $matricula->iYAcadId, $matricula->iSedeId, $fecha]
        );
    }
}
