<?php

namespace App\Services\acad;

use App\Models\acad\CompetenciaCurso;
use App\Models\acad\Matricula;
use App\Models\eval\ResultadoCompetencia;
use App\Repositories\grl\PersonasRepository;
use Exception;
use stdClass;

class ReportesAcademicosService
{
    public static function generarReporteAcademicoProgreso($usuario, $iCredPerfIdEstudiante, $iYAcadId)
    {
        $yearAcademico = YearAcademicosService::obtenerYearAcademico($iYAcadId);
        $persona = PersonasRepository::obtenerPersonaPorId($usuario->iPersId);
        $matricula = MatriculasService::obtenerDetallesMatriculaEstudiante($iCredPerfIdEstudiante, $iYAcadId);
        $ie = InstitucionesEducativasService::obtenerInstitucionEducativa($matricula->iIieeId);
        $tutor = DocentesCursosService::obtenerTutorSalonIe($iYAcadId, $matricula->iSedeId, $matricula->iNivelGradoId, $matricula->iSeccionId);
        $fechasInicioFin = CalendariosAcademicosService::obtenerCalendarioFechasInicioFinSede($iYAcadId, $matricula->iSedeId);
        $htmlcontent = view('acad.estudiante.reportes_academicos.progreso.reporte_progreso_body', compact('persona', 'matricula', 'ie', 'tutor', 'yearAcademico', 'fechasInicioFin'))->render();
        //$footerHtml = view('acad.estudiante.reportes_academicos.progreso.reporte_progreso_footer', compact('persona'))->render();
        //$fullHtml = $htmlcontent . $footerHtml;
        $archivoHtml = $usuario->iPersId . '_reporte_progreso.html';
        $tempPath = storage_path('app\\' . $archivoHtml);
        file_put_contents($tempPath, $htmlcontent);

        $exePath   = env('WEASYPRINT_PATH');
        $inputHtml = storage_path('app\\' . $archivoHtml);
        $outputPdf = storage_path('app\\' . $usuario->iPersId . '_reporte_progreso.pdf');
        $cmd = "\"{$exePath}\" \"{$inputHtml}\" \"{$outputPdf}\"";
        $output = shell_exec($cmd . ' 2>&1');
        if (!file_exists($outputPdf)) {
            throw new Exception("Error generando PDF: {$output}");
        }
        unlink($inputHtml);
        return $outputPdf;
    }

    public static function obtenerReporteAcademicoProgreso($iCredPerfIdEstudiante, $iYAcadId)
    {
        $matricula = MatriculasService::obtenerDetallesMatriculaEstudiante($iCredPerfIdEstudiante, $iYAcadId);
        /*Se obtienen los cursos por IE debido a que, si solo se obtiene los cursos a los que esta matriculado el alumno, la libreta podria salir con cantidad
        de cursos distinta por alumno*/
        $cursos = ReportesAcademicosService::obtenerCursosPorIe($matricula->iSedeId, $iYAcadId, $matricula->iNivelGradoId);
        foreach ($cursos as $curso) {
            $curso->competencias = ReportesAcademicosService::obtenerCompetenciasPorCurso(
                $curso->iNivelTipoId,
                $curso->iCursoId,
            );
            foreach ($curso->competencias as $competencia) {
                $competencia->notas=[];
                for ($i = 1; $i <= 5; $i++) {
                    $resultadoCompetencia = ReportesAcademicosService::obtenerResultadosPorCompetencia(
                        $matricula->iMatrId,
                        $competencia->iCompetenciaId ?? 0,
                        $curso->iCursosNivelGradId,
                        $i,
                    );
                    if ($resultadoCompetencia) {
                        array_push($competencia->notas, $resultadoCompetencia->cNivelLogro);
                    } else {
                        array_push($competencia->notas, '-');
                    }
                }
            }
        }
        return $cursos;
    }

    public static function obtenerCursosPorIe($iSedeId, $iYAcadId, $iNivelGradoId)
    {
        return CompetenciaCurso::selCursosPorIe($iSedeId, $iYAcadId, $iNivelGradoId);
    }

    public static function obtenerCompetenciasPorCurso($iNivelTipoId, $iCursoId)
    {
        return CompetenciaCurso::selCompetenciasPorCurso($iNivelTipoId, $iCursoId);
    }

    public static function obtenerResultadosPorCompetencia($iMatrId, $iCompetenciaId, $iCursosNivelGradId, $iPeriodoId)
    {
        return ResultadoCompetencia::selResultadosPorCompetencia($iMatrId, $iCompetenciaId, $iCursosNivelGradId, $iPeriodoId);
    }

    public static function obtenerResultadoParaGrafico($iCredPerfIdEstudiante, $iYAcadId, $iIeCursoId)
    {
        $matricula = MatriculasService::obtenerDetallesMatriculaEstudiante($iCredPerfIdEstudiante, $iYAcadId);
        $curso = IeCursosService::obtenerCursoPorIeCurso($iIeCursoId);
        $competencias = self::obtenerCompetenciasPorCurso($matricula->iNivelTipoId, $curso->iCursoId);
        $data = [];
        foreach ($competencias as $competencia) {
            $fila = new stdClass();
            $fila->competencia = $competencia->cCompetenciaNombre;
            $fila->periodos = [];
            $fila->resultado = [];
            for ($i = 1; $i <= 5; $i++) {
                $resultado = ResultadoCompetencia::selResultadosPorCompetencia($matricula->iMatrId, $competencia->iCompetenciaId, $curso->iCursosNivelGradId, $i);
                if ($resultado) {
                    array_push($fila->periodos, $i == 5 ? 'Nota final' : ('Periodo ' . $i));
                    array_push($fila->resultado, $resultado->iResultado);
                }
            }
            array_push($data, $fila);
        }
        return $data;
    }
}
