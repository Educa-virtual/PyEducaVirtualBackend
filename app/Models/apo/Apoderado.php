<?php

namespace App\Models\apo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Apoderado extends Model
{
    public static function selEstudiantesPorApoderado($iPersId)
    {
        return DB::select("SELECT apo.iEstudianteId,per.cPersPaterno, cPersMaterno, cPersNombre,
  CONCAT(cPersNombre, ' ',cPersPaterno, ' ',cPersMaterno) AS cNombreCompleto
  FROM apo.apoderado AS apo
  INNER JOIN acad.estudiantes AS est ON est.iEstudianteId=apo.iEstudianteId
  INNER JOIN grl.personas AS per ON per.iPersId=est.iPersId
  WHERE apo.iPersId=? AND apo.iEstado=1
  ORDER BY cPersNombre, cPersPaterno", [$iPersId]);
    }

    public static function selEstudianteApoderado($iPersIdApoderado, $iEstudianteId)
    {
        return DB::selectOne("SELECT * FROM apo.apoderado AS apo WHERE apo.iPersId=? AND apo.iEstudianteId=?", [$iPersIdApoderado, $iEstudianteId]);
    }
}
