<?php

namespace App\Http\Controllers\acad;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Services\acad\ReportesAcademicosService;
use App\Http\Controllers\Controller;
use App\Services\acad\EstudiantesService;
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
            $outputPdf = ReportesAcademicosService::generarReporteAcademicoProgreso(Auth::user()->iPersId, $request->header('iCredEntPerfId'), $iYAcadId);
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
            $outputPdf = ReportesAcademicosService::generarReporteAcademicoProgreso($dataEstudiante->iPersId, $dataEstudiante->iCredEntPerfId, $iYAcadId);
            return response()->download($outputPdf)->deleteFileAfterSend(true);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function obtenerDataReporteEstudiante($iYAcadId, Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ESTUDIANTE]]);
            $data = ReportesAcademicosService::obtenerReporteAcademicoProgreso($request->header('iCredEntPerfId'), $iYAcadId);
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
            $data = ReportesAcademicosService::obtenerReporteAcademicoProgreso($dataEstudiante->iCredEntPerfId, $iYAcadId);
            return FormatearMensajeHelper::ok("Datos obtenidos", $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
