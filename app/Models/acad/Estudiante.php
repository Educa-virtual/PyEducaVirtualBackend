<?php

namespace App\Models\acad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Estudiante extends Model
{
    public static function selIdEstudiantePorIdPersona($iEstudianteId)
    {
        return DB::selectOne("SELECT iEstudianteId FROM acad.estudiantes WHERE iPersId=?", [$iEstudianteId]);
    }

    public static function selIdCredIdPersEstudiantePorIeDocumento($cPersDocumento, $iSedeId)
    {
        return DB::selectOne("SELECT TOP 1 iCredEntPerfId, per.iPersId
FROM seg.credenciales_entidades_perfiles AS cep
INNER JOIN seg.credenciales_entidades AS ce ON ce.iCredEntId=cep.iCredEntId
INNER JOIN seg.credenciales AS cred ON cred.iCredId=ce.iCredId
INNER JOIN acad.estudiantes AS est ON est.iPersId=cred.iPersId
INNER JOIN acad.matricula AS mat ON mat.iEstudianteId=est.iEstudianteId
INNER JOIN grl.personas AS per ON per.iPersId=est.iPersId
WHERE per.cPersDocumento=? AND mat.iSedeId=?", [$cPersDocumento, $iSedeId]);
    }
}
