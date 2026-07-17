<?php

namespace App\Http\Controllers\acad;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\acad\ListarCalendarioPeriodosRequest;
use App\Models\acad\CalendarioTurno;
use Illuminate\Http\Request;

class CalendarioTurnoController extends Controller
{
    public function verCalendarioTurno(Request $request)
    {
        try {
            $data = CalendarioTurno::selCalendarioTurno($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch(\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarCalendarioTurno(Request $request)
    {
        try {
            $data = CalendarioTurno::insCalendarioTurno($request);
            return FormatearMensajeHelper::ok('Se proceso el calendario', $data);
        } catch(\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarCalendarioTurno(Request $request)
    {
        try {
            $data = CalendarioTurno::updCalendarioTurno($request);
            return FormatearMensajeHelper::ok('Se actualizo el calendario', $data);
        } catch(\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
