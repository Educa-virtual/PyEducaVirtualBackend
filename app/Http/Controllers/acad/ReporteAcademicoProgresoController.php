<?php

namespace App\Http\Controllers\acad;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Helpers\VerifyHash;
use App\Services\acad\ReportesAcademicosService;
use App\Http\Controllers\Controller;
use App\Services\acad\EstudiantesService;
use App\Services\acad\MatriculasService;
use App\Services\apo\ApoderadosService;
use App\Services\seg\UsuariosService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ReporteAcademicoProgresoController extends Controller
{

    public function generarReporteEstudiantePdf($iYAcadId, Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ESTUDIANTE]]);
            $matricula = MatriculasService::obtenerDetallesMatriculaEstudiantePorCredPerfId($request->header('iCredEntPerfId'), $iYAcadId);
            $outputPdf = ReportesAcademicosService::generarReporteAcademicoProgresoPdf($matricula);
            return response()->download($outputPdf)->deleteFileAfterSend(true);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function generarReporteDirectorPdf($iYAcadId, $cPersDocumento, Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);
            $detallesCredencial = UsuariosService::obtenerDetallesCredencialEntidad($request->header('iCredEntPerfId'));
            $dataEstudiante = EstudiantesService::obtenerIdCredIdPersEstudiantePorIeDocumento($cPersDocumento, $detallesCredencial->iSedeId);
            $matricula = MatriculasService::obtenerDetallesMatriculaEstudiantePorCredPerfId($dataEstudiante->iCredEntPerfId, $iYAcadId);
            $outputPdf = ReportesAcademicosService::generarReporteAcademicoProgresoPdf($matricula);
            return response()->download($outputPdf)->deleteFileAfterSend(true);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function generarReporteApoderadoPdf($iMatrId)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::APODERADO]]);
            $matricula = MatriculasService::obtenerDetalleMatriculaEstudiantePorId(VerifyHash::decodesxId($iMatrId));
            ApoderadosService::estudiantePerteneceApoderado(Auth::user()->iPersId, $matricula->iEstudianteId);
            $outputPdf = ReportesAcademicosService::generarReporteAcademicoProgresoPdf($matricula);
            return response()->download($outputPdf)->deleteFileAfterSend(true);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function obtenerDataReporteEstudiante($iYAcadId, Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ESTUDIANTE]]);
            $matricula = MatriculasService::obtenerDetallesMatriculaEstudiantePorCredPerfId($request->header('iCredEntPerfId'), $iYAcadId);
            $data = ReportesAcademicosService::obtenerReporteAcademicoProgreso($matricula, $iYAcadId);
            return FormatearMensajeHelper::ok("Datos obtenidos", $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function obtenerDataReporteDirector($iYAcadId, $cPersDocumento, Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);
            $detallesCredencial = UsuariosService::obtenerDetallesCredencialEntidad($request->header('iCredEntPerfId'));
            $dataEstudiante = EstudiantesService::obtenerIdCredIdPersEstudiantePorIeDocumento($cPersDocumento, $detallesCredencial->iSedeId);
            $matricula = MatriculasService::obtenerDetallesMatriculaEstudiantePorCredPerfId($dataEstudiante->iCredEntPerfId, $iYAcadId);
            $data = ReportesAcademicosService::obtenerReporteAcademicoProgreso($matricula);
            return FormatearMensajeHelper::ok("Datos obtenidos", $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }



    /*public function obtenerReporteProgresoEstudiante($iEstudianteId, $iMatrId, Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::APODERADO]]);
            $request->merge(['iMatrId' => VerifyHash::decodesxId($iMatrId)]);
            $matricula=MatriculasService::obtenerMatriculaPorId($request);
            $data = MatriculasService::obtenerMatriculasEstudiante($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }*/

    public function obtenerDataReporteApoderado($iMatrId, Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::APODERADO]]);
            $matricula = MatriculasService::obtenerDetalleMatriculaEstudiantePorId(VerifyHash::decodesxId($iMatrId));
            ApoderadosService::estudiantePerteneceApoderado(Auth::user()->iPersId, $matricula->iEstudianteId);
            $data = ReportesAcademicosService::obtenerReporteAcademicoProgreso($matricula);
            return FormatearMensajeHelper::ok("Datos obtenidos", $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
