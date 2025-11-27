<?php

namespace App\Models\eval;

use Illuminate\Support\Facades\DB;

class LogroAlcanzado
{
    public static function selPeriodosEvaluacionSede($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->iSedeId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC eval.Sp_SEL_periodosEvaluacionSede $placeholders", $parametros);
    }

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

    public static function actualizarLogro($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->idDocCursoId,
            $request->iCompetenciaId,
            $request->iResultadoCompId,
            $request->iPeriodoId,
            $request->iDetMatrId,
            $request->iResultado,
            $request->cDescripcion,
            $request->cNivelLogro,
            $request->iEscalaCalifId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC eval.Sp_UPD_logroAlcanzadoEstudiante $placeholders", $parametros);
    }

    public static function selEscalasCalificacionCurso($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->idDocCursoId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC eval.Sp_SEL_escalasCalificacionCurso $placeholders", $parametros);
    }

    public static function updEscalaCalificacionCurso($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->idDocCursoId,
            $request->iTipoCalificacionId,
            $request->jsonEscalas,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC eval.Sp_INS_UPD_escalaCalificacionCurso $placeholders", $parametros);
    }

    public static function selLogrosAlcanzadosMasivo($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->idDocCursoId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectResultSets("EXEC eval.Sp_SEL_logrosAlcanzadosMasivo $placeholders", $parametros);
    }

    public static function selLogrosAlcanzadosPeriodo($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->idDocCursoId,
            $request->iPeriodoId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectResultSets("EXEC eval.Sp_SEL_logrosAlcanzadosPeriodo $placeholders", $parametros);
    }
}
