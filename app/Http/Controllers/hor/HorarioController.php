<?php

namespace App\Http\Controllers\hor;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Services\acad\MatriculasService;
use App\Services\hor\HorariosService;
use Illuminate\Http\Request;
use Exception;

class HorarioController extends Controller
{
    public function obtenerHorario(Request $request) {
        try {
            $matricula = MatriculasService::obtenerDetallesMatriculaEstudiante($request->header('iCredEntPerfId'), $request->iYAcadId);
            $horario = HorariosService::obtenerHorario($matricula);
            return FormatearMensajeHelper::ok('Datos obtenidos', ['matricula' => $matricula, 'horario' => $horario]);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}

