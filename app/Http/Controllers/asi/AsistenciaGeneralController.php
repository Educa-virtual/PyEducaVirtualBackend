<?php

namespace App\Http\Controllers\asi;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Services\acad\MatriculasService;
use App\Services\acad\YearAcademicosService;
use App\Services\asi\AsistenciaGeneralService;
use App\Services\seg\UsuariosService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AsistenciaGeneralController extends Controller
{
    public function obtenerAsistenciaEstudiantePorFecha($anio, $mes, Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ESTUDIANTE]]);
            $detallesCredencial = UsuariosService::obtenerDetallesCredencialEntidad($request->header('iCredEntPerfId'));
            $yearAcademico = YearAcademicosService::obtenerYearAcademicoPorAnio($anio);
            $params = [Auth::user()->iPersId, $yearAcademico->iYAcadId, $detallesCredencial->iSedeId, NULL];
            $matricula = MatriculasService::obtenerDetalleMatriculaEstudiante($params);
            $asistencia = AsistenciaGeneralService::obtenerAsistenciaEstudiantePorPeriodo($matricula, $anio, $mes);
            return FormatearMensajeHelper::ok('Datos obtenidos', $asistencia);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
