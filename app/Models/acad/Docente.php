<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class Docente
{
    public static function selDocentePorId($iDocenteId) {
        return DB::selectOne("SELECT doc.iDocenteId, per.cPersPaterno, per.cPersMaterno, per.cPersNombre
FROM acad.docentes AS doc
INNER JOIN grl.personas AS per ON per.iPersId=doc.iPersId
WHERE doc.iDocenteId=?", [$iDocenteId]);
    }
}
