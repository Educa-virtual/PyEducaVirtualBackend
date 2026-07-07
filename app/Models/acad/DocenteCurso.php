<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class DocenteCurso
{
    public static function selTutorSalonIe($iYAcadId, $iSedeId, $iNivelGradoId, $iSeccionId)
    {
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

    public static function selDocenteCursos($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iConfigId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::select("EXEC acad.Sp_SEL_docenteCursos $placeholders", $parametros);
    }

    public static function selDocenteCursoHistorial($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->idDocCursoId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::select("EXEC acad.Sp_SEL_docenteCursoHistorial $placeholders", $parametros);
    }

    public static function insDocenteCurso($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iConfigId,
            $request->iDocenteId,
            $request->iIeCursoId,
            $request->iDetConfId,
            $request->iDocCursoHorasLectivas,
            $request->cDocCursoObservaciones,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::update("EXEC acad.Sp_INS_docenteCurso $placeholders", $parametros);
    }

    public static function updDocenteCurso($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->idDocCursoId,
            $request->iDocenteId,
            $request->iDocCursoHorasLectivas,
            $request->cDocCursoObservaciones,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::update("EXEC acad.Sp_UPD_docenteCurso $placeholders", $parametros);
    }

    public static function updDocenteCursoEstado($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->idDocCursoId,
            $request->bActivo,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::update("EXEC acad.Sp_UPD_docenteCursoEstado $placeholders", $parametros);
    }

    public static function delDocenteCurso($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->idDocCursoId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::update("EXEC acad.Sp_DEL_docenteCurso $placeholders", $parametros);
    }
}
