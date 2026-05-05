<?php

namespace App\Models\apo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Apoderado extends Model
{
    public static function selApoderados(Object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEstudianteId,
            $request->cEstCodigo,
            $request->iPersIdEstudiante,
            $request->iPersIdApoderado,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC apo.SP_DEL_apoderado $placeholders", $parametros);
    }

    public static function insApoderado(Object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPersId,
            $request->iEstudianteId,
            $request->iTipoFamiliarId,
            $request->cObservacion,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC apo.SP_INS_apoderado $placeholders", $parametros);
    }

    public static function updApoderado(Object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iApoderadoId,
            $request->iPersId,
            $request->iTipoFamiliarId,
            $request->cObservacion,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC apo.SP_UPD_apoderado $placeholders", $parametros);
    }

    public static function delApoderado(Object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iApoderadoId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC apo.SP_DEL_apoderado $placeholders", $parametros);
    }

    public static function selApoderado(Object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iApoderadoId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC apo.SP_DEL_apoderado $placeholders", $parametros);
    }

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

    public static function insApoderadosDesdeArchivo($json, $iSesionId)
    {
        foreach ($json as $item) {
            $jsonItem = json_encode($item, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            DB::statement("EXEC apo.SP_INS_ApoderadosEstudiantes @json=?, @iSesionId=?", [$jsonItem, $iSesionId]);
        }
    }
}
