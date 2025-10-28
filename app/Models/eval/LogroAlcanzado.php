<?php

namespace App\Models\eval;

use Illuminate\Support\Facades\DB;

class LogroAlcanzado
{
    public static function selDatosCursoDocente($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->idDocCursoId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC eval.Sp_SEL_datosCursoDocente $placeholders", $parametros);
    }

    public static function selLogrosAlcanzadosEstudiante($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->idDocCursoId,
            $request->iDetMatrId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC eval.Sp_SEL_logrosAlcanzadosEstudiante $placeholders", $parametros);
    }

    public static function guardarLogro($request)
    {
        $parametros = [
            $request->jsonLogro,
            $request->opcion,
            $request->header('iCredId')
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC eval.Sp_INS_resultadoXcompetencias $placeholders", $parametros);
    }

    public static function actualizarLogro($request)
    {
        $parametros = [
            $request->jsonLogro,
            $request->header('iCredId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC eval.Sp_UPD_resultadoXperiodoDetMatricula $placeholders", $parametros);
    }
}
