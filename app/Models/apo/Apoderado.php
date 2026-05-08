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
        return DB::select("EXEC apo.SP_SEL_apoderados $placeholders", $parametros);
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
        return DB::selectOne("EXEC apo.SP_INS_apoderado $placeholders", $parametros);
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
        return DB::selectOne("EXEC apo.SP_UPD_apoderado $placeholders", $parametros);
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
        return DB::selectOne("EXEC apo.SP_SEL_apoderado $placeholders", $parametros);
    }

    public static function selPersonaApoderado(Object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEstudianteId,
            $request->iTipoIdentId,
            $request->cPersDocumento,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC apo.SP_SEL_personaApoderado $placeholders", $parametros);
    }
}
