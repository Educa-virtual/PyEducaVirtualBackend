<?php

namespace App\Services\acad;

use App\Models\acad\CompetenciaCurso;
use App\Models\acad\Matricula;
use App\Models\eval\ResultadoCompetencia;
use App\Repositories\grl\PersonasRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ReportesAcademicosService
{
    public static function generarReporteAcademicoProgreso($usuario, $iCredPerfIdEstudiante, $iYAcadId)
    {
        $pdf = App::make('snappy.pdf.wrapper');
        $persona = PersonasRepository::obtenerPersonaPorId($usuario->iPersId);
        $matricula = self::obtenerDetallesMatricula($iCredPerfIdEstudiante, $iYAcadId);
        $htmlcontent = view('acad.estudiante.reportes_academicos.progreso.reporte_progreso_body', compact('matricula'))->render();
        $headerHtml = view('acad.estudiante.reportes_academicos.progreso.reporte_progreso_header')->render();
        $footerHtml = view('acad.estudiante.reportes_academicos.progreso.reporte_progreso_footer', compact('persona'))->render();
        $pdf->loadHtml($htmlcontent)
            ->setPaper('a4', 'portrait')
            ->setOption('disable-external-links', true)
            ->setOption('enable-local-file-access', true)
            ->setOption('disable-smart-shrinking', true)
            ->setOption('margin-top', '3cm')
            ->setOption('margin-bottom', '2cm')
            ->setOption('footer-left', "PÁGINA [page] DE [toPage]")
            ->setOption('footer-font-size', 8)
            ->setOption('header-html', $headerHtml)
            ->setOption('footer-html', $footerHtml)
            ->setOption('dpi', 300);
        return $pdf;
    }

    public static function obtenerDetallesMatricula($iCredEndPerfId, $iYAcadId)
    {
        return Matricula::selDetalleMatriculaEstudiante($iCredEndPerfId, $iYAcadId);
    }

    public static function obtenerCursosPorIe($iSedeId, $iNivelGradoId)
    {
        return CompetenciaCurso::selCursosPorIe($iSedeId, $iNivelGradoId);
    }

    public static function obtenerCompetenciasPorCurso($iNivelTipoId, $iCursoId)
    {
        return CompetenciaCurso::selCompetenciasPorCurso($iNivelTipoId, $iCursoId);
    }

    public static function obtenerResultadosPorCompetencia($iMatrId, $iCompetenciaId, $iIeCursoId, $iPeriodoId)
    {
        return ResultadoCompetencia::selResultadosPorCompetencia($iMatrId, $iCompetenciaId, $iIeCursoId, $iPeriodoId);
    }
}
