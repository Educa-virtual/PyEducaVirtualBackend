<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class Matricula
{
    public static function selMatriculaParametros($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::selectOne("exec acad.Sp_SEL_matriculaParametros $placeholders", $parametros);
    }

    public static function selGradoSeccionTurnoConf($request)
    {
        $parametros = [
            $request->opcion,
            $request->iSedeId,
            $request->iYAcadId,
            $request->iNivelGradoId,
            $request->iSeccionId,
            $request->iTurnoId,
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::select("exec acad.Sp_SEL_gradoSeccionTurnoConf $placeholders", $parametros);
    }

    public static function insMatricula($request)
    {
        $parametros = [
            $request->iEstudianteId,
            $request->iYAcadId,
            $request->iTipoMatrId,
            $request->iSedeId,
            $request->iNivelGradoId,
            $request->iTurnoId,
            $request->iSeccionId,
            $request->dtMatrFecha,
            $request->cMatrObservaciones,
            $request->header('iCredEntPerfId'),
            $request->iMatrEstado,
            $request->iMatrNEE,
            $request->iSemAcadId,
            $request->iCurrId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::selectOne("exec acad.Sp_INS_matricula $placeholders", $parametros);
    }

    public static function updMatricula($request)
    {
        $parametros = [
            $request->iMatrId,
            $request->iEstudianteId,
            $request->iTipoMatrId,
            $request->iSedeId,
            $request->iNivelGradoId,
            $request->iTurnoId,
            $request->iSeccionId,
            $request->dtMatrFecha,
            $request->cMatrObservaciones,
            $request->header('iCredEntPerfId'),
            $request->iMatrEstado,
            $request->iMatrNEE,
            $request->iSemAcadId,
            $request->iCurrId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::selectOne("exec acad.Sp_UPD_matricula $placeholders", $parametros);
    }

    public static function selMatriculas($request)
    {
        $parametros = [
            $request->iSedeId,
            $request->iSemAcadId,
            $request->iYAcadId,
            $request->iNivelGradoId,
            $request->iSeccionId,
            $request->iTurnoId,
            $request->iEstudianteId,
            null,
            null,
            null,
            null,
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::select("exec acad.Sp_SEL_matriculas $placeholders", $parametros);
    }

    public static function selMatricula($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->iSedeId,
            $request->iMatrId,
            $request->iEstudianteId,
            $request->cEstCodigo,
            $request->iTipoIdentId,
            $request->cPersDocumento,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::selectOne("exec acad.Sp_SEL_matricula $placeholders", $parametros);
    }

    public static function selMatriculaPorId($request)
    {
        $parametros = [
            $request->iMatrId,
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::selectOne("exec acad.Sp_SEL_matriculaPorId $placeholders", $parametros);
    }

    public static function delMatriculaPorId($request)
    {
        $parametros = [
            $request->iMatrId,
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::delete("exec acad.Sp_DEL_matriculaPorId $placeholders", $parametros);
    }

    public static function selDetalleMatriculaEstudiante($params)
    {
        return DB::selectOne('EXEC [acad].[SP_SEL_detalleMatriculaEstudiante] @iPersId=?, @iYAcadId=?, @iSedeId=?, @iMatrId=?', $params);
    }

    public static function selCursosMatricula($iMatrId)
    {
        return DB::select('EXEC [acad].[SP_SEL_cursosMatricula] @iMatrId=?', [$iMatrId]);
    }
}
