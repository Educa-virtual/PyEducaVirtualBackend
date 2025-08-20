<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class Matricula
{
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

    public static function selNivelGrado($request)
    {
        $data = DB::select("SELECT ng.iNivelGradoId, n.cNivelNombre, nt.cNivelTipoNombre, c.cCicloNombre, g.cGradoAbreviacion, g.cGradoNombre
                FROM acad.nivel_grados ng
                    JOIN acad.grados g ON ng.iGradoId = g.iGradoId
                    JOIN acad.nivel_ciclos nc ON nc.iNivelCicloId = ng.iNivelCicloId
                    JOIN acad.ciclos c ON nc.iCicloId = c.iCicloId
                    JOIN acad.nivel_tipos nt ON nc.iNivelTipoId = nt.iNivelTipoId
                    JOIN acad.niveles n ON nt.iNivelId = n.iNivelId");
        return $data;
    }

    public static function selDeterminarGradoEstudiante($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iEstudianteId,
            $request->iYAcadId,
            $request->iSedeId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("exec acad.Sp_SEL_determinarGradoEstudiante $placeholders", $parametros);
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
            $request->cMatrObservacion,
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::insert("exec acad.Sp_INS_matricula $placeholders", $parametros);
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
            null,
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

    public static function selDetalleMatriculaEstudiante($iCredEntPerfId, $iYAcadId)
    {
        return DB::selectOne("EXEC [acad].[SP_SEL_detalleMatriculaEstudiante] @iCredEntPerfId=?, @iYAcadId=?", [$iCredEntPerfId, $iYAcadId]);
    }
}
