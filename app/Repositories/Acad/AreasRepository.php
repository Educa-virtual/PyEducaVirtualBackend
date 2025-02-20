<?php

namespace App\Repositories\Acad;

use Illuminate\Support\Facades\DB;

class AreasRepository
{
    public static function obtenerAreaPorNivelGradId($iCursosNivelGradId)
    {
        $area = DB::selectOne('SELECT cCursoNombre, cGradoNombre, cGradoAbreviacion, cNivelTipoNombre
FROM acad.cursos AS acur
INNER JOIN acad.cursos_niveles_grados   AS acunig   ON acunig.iCursoId=acur.iCursoId
INNER JOIN acad.nivel_grados            AS angr     ON angr.iNivelGradoId=acunig.iNivelGradoId
INNER JOIN acad.grados                  AS agr      ON agr.iGradoId=angr.iGradoId
INNER JOIN acad.nivel_ciclos			AS anici	ON anici.iNivelCicloId=angr.iNivelCicloId
INNER JOIN acad.nivel_tipos				AS aniti	ON aniti.iNivelTipoId=anici.iNivelTipoId
INNER JOIN acad.niveles					AS ani		ON ani.iNivelId = aniti.iNivelId
WHERE iCursosNivelGradId=?', [$iCursosNivelGradId]);
        return $area;
    }

    public static function obtenerMatrizPorEvaluacionArea($iEvaluacionId, $iCursosNivelGradId)
    {
        return DB::select('SELECT
        p.iPreguntaId,p.cPregunta,iEncabPregId,
        cmp.cCompetenciaNombre,
        cmp.cCompetenciaDescripcion,
        cc.cCapacidadNombre,
        cc.cCapacidadDescripcion,
        d.cDesempenoDescripcion,
        d.cDesempenoConocimiento,
        p.iPreguntaNivel,
        p.iPreguntaPeso,
        (SELECT TOP 1 alt.cAlternativaLetra FROM ere.alternativas AS alt WHERE alt.iPreguntaId=p.iPreguntaId AND
        alt.iEstado=1 AND bAlternativaCorrecta=1) AS cAlternativaLetra
    FROM
        [ere].[evaluacion_preguntas] ep
    LEFT JOIN
        [ere].[preguntas] p ON ep.iPreguntaId = p.iPreguntaId
    LEFT JOIN
        [ere].[desempenos] d ON p.iDesempenoId = d.iDesempenoId
    LEFT JOIN
        [acad].[curriculo_capacidades] cc ON d.iCapacidadId = cc.iCapacidadId
    LEFT JOIN
        [acad].[curriculo_competencias] cmp ON cc.iCompetenciaId = cmp.iCompetenciaId
    LEFT JOIN
        [ere].[evaluacion] e ON ep.iEvaluacionId = e.iEvaluacionId
    WHERE
        ep.iEvaluacionId = ? AND  p.iCursosNivelGradId=? AND bPreguntaEstado=1
       ORDER BY iEncabPregId, iPreguntaId', [$iEvaluacionId, $iCursosNivelGradId]);
    }
}
