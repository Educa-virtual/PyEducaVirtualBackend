<?php

namespace App\Http\Controllers\hor;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Services\acad\MatriculasService;
use App\Services\hor\HorariosService;
use App\Services\seg\UsuariosService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class HorarioController extends Controller
{
    public function obtenerHorario($iYAcadId, Request $request) {
        try {
            $detallesCredencial = UsuariosService::obtenerDetallesCredencialEntidad($request->header('iCredEntPerfId'));
            $params = [Auth::user()->iPersId, $iYAcadId, $detallesCredencial->iSedeId, NULL];
            $matricula = MatriculasService::obtenerDetalleMatriculaEstudiante($params);
            $horario = HorariosService::obtenerHorario($matricula);
            return FormatearMensajeHelper::ok('Datos obtenidos', ['matricula' => $matricula, 'horario' => $horario]);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}

