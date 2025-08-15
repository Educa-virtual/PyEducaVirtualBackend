<?php

namespace App\Services\acad;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ReportesAcademicosService
{
    public static function generarReporteAcademicoProgreso($usuarioActual, $iCredPerfIdEstudiante)
    {
        //, compact('resultados')
        $pdf = App::make('snappy.pdf.wrapper');
        $matricula=self::obtenerDetallesMatricula($iCredPerfIdEstudiante);
        $htmlcontent = view('acad.estudiante.reportes_academicos.progreso.reporte_progreso_body', compact('usuarioActual', 'matricula'))->render();
        $headerHtml = view('acad.estudiante.reportes_academicos.progreso.reporte_progreso_header')->render();
        $footerHtml = view('acad.estudiante.reportes_academicos.progreso.reporte_progreso_footer')->render();
        $pdf->loadHtml($htmlcontent)
            ->setPaper('a4', 'portrait')
            ->setOption('disable-external-links', true)
            ->setOption('enable-local-file-access', true)
            ->setOption('disable-smart-shrinking', true)
            ->setOption('margin-top', '3cm')
            ->setOption('margin-bottom', '2cm')
            ->setOption('footer-left', "PAGINA [page] DE [toPage]")
            ->setOption('footer-font-size', 8)
            ->setOption('header-html', $headerHtml)
            ->setOption('footer-html', $footerHtml)
            ->setOption('dpi', 300);
        return $pdf;
    }

    public static function obtenerDetallesMatricula($iCredEndPerfId) {
        return DB::selectOne("EXEC [acad].[SP_SEL_detalleMatriculaEstudiante] @iCredEntPerfId=?", [$iCredEndPerfId]);
    }

    public static function obtenerCursosPorIe($iSedeId, $iNivelGradoId)
    {
        return DB::select("SELECT iesc.iIeCursoId,acunig.iCursosNivelGradId,acur.iCursoId,iNivelTipoId,cCursoNombre,
(SELECT COUNT(compcur.iCompetenciaId)
FROM acad.competencias_cursos AS compcur
INNER JOIN acad.curriculo_competencias AS curcomp ON curcomp.iCompetenciaId=compcur.iCompetenciaId
WHERE compcur.iEstado=1 AND compcur.iNivelTipoId=nivgr.iNivelGradoId AND compcur.iCursoId=acur.iCursoId) AS iCantidadFilas
FROM acad.ies_cursos AS iesc
INNER JOIN acad.programas_estudios AS proge ON proge.iProgId=iesc.iProgId
INNER JOIN acad.cursos_niveles_grados AS acunig ON acunig.iCursosNivelGradId=iesc.iCursosNivelGradId
INNER JOIN acad.nivel_grados AS nivgr ON nivgr.iNivelGradoId=acunig.iNivelGradoId
INNER JOIN acad.nivel_ciclos AS nivcic ON nivcic.iNivelCicloId=nivgr.iNivelCicloId
INNER JOIN acad.cursos AS acur ON acur.iCursoId=acunig.iCursoId
WHERE iesc.iEstado=1 AND iSedeId=? AND nivgr.iNivelGradoId=?
ORDER BY cCursoNombre", [$iSedeId, $iNivelGradoId]);
    }

    public static function obtenerCompetenciasPorCurso($iNivelTipoId, $iCursoId)
    {
        return DB::select("SELECT compcur.iCompetenciaId, cCompetenciaNombre
FROM acad.competencias_cursos AS compcur
INNER JOIN acad.curriculo_competencias AS curcomp ON curcomp.iCompetenciaId=compcur.iCompetenciaId
WHERE compcur.iEstado=1 AND compcur.iNivelTipoId=? AND compcur.iCursoId=?", [$iNivelTipoId, $iCursoId]);
    }

    public static function obtenerResultadosPorCompetencia($iMatrId, $iCompetenciaId, $iIeCursoId, $iPeriodoId)
    {
        return DB::selectOne("SELECT iMatrId, iPeriodoId, cNivelLogro, cDescripcion
FROM eval.resultado_competencias AS rescom
INNER JOIN acad.detalle_matriculas AS detmat ON detmat.iDetMatrId=rescom.iDetMatrId
WHERE rescom.iEstado=1 AND detmat.iMatrId=? AND iCompetenciaId=? AND iIeCursoId=?
AND iPeriodoId=?", [$iMatrId, $iCompetenciaId, $iIeCursoId, $iPeriodoId]);
    }
}
