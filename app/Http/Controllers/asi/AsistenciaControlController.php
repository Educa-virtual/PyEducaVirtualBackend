<?php

namespace App\Http\Controllers\asi;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Services\acad\MatriculasService;
use App\Services\acad\YearAcademicosService;
use App\Services\asi\AsistenciaControlService;
use App\Services\seg\UsuariosService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsistenciaControlController extends Controller
{
    public function obtenerAsistenciaEstudiantePorFecha($fecha, Request $request)
    {
        try {
            $detallesCredencial = UsuariosService::obtenerDetallesCredencialEntidad($request->header('iCredEntPerfId'));
            $fechaCarbon = Carbon::parse($fecha);
            $yearAcademico = YearAcademicosService::obtenerYearAcademicoPorAnio($fechaCarbon->year());
            $params = [Auth::user()->iPersId, $yearAcademico->iYAcadId, $detallesCredencial->iSedeId, NULL];
            $matricula = MatriculasService::obtenerDetalleMatriculaEstudiante($params);
            $asistencia = AsistenciaControlService::obtenerAsistenciaEstudiantePorFecha($matricula->iEstudianteId, $matricula->iYAcadId, $matricula->iSedeId, $fechaCarbon->format('Ymd'));
            return FormatearMensajeHelper::ok('Datos obtenidos', $asistencia);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
