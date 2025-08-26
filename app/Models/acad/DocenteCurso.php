<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class DocenteCurso
{
    public static function selTutorSalonIe($iYAcadId, $iSedeId, $iNivelGradoId, $iSeccionId) {
        return DB::selectOne('SELECT cPersPaterno, cPersMaterno, cPersNombre
FROM acad.docente_cursos AS doccur
INNER JOIN acad.ies_cursos AS iec ON iec.iIeCursoId=doccur.iIeCursoId
INNER JOIN acad.docentes AS doc ON doc.iDocenteId=doccur.iDocenteId
INNER JOIN grl.personas AS per ON per.iPersId=doc.iPersId
INNER JOIN acad.programas_estudios AS proge ON proge.iProgId=iec.iProgId
INNER JOIN acad.cursos_niveles_grados AS cng ON cng.iCursosNivelGradId=iec.iCursosNivelGradId
INNER JOIN acad.nivel_grados AS ng ON cng.iNivelGradoId=ng.iNivelGradoId
INNER JOIN acad.nivel_ciclos AS nc ON nc.iNivelCicloId=ng.iNivelCicloId
WHERE doccur.iEstado=1 AND iYAcadId=? AND iSedeId=? AND iCursoId=13 AND cng.iNivelGradoId=?
AND iSeccionId=?', [$iYAcadId, $iSedeId, $iNivelGradoId, $iSeccionId]);
    }
}
