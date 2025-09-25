<?php

namespace App\Http\Controllers\acad;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Services\acad\BuzonSugerenciasDirectorService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Gate;

class BuzonSugerenciaDirectorController extends Controller
{
    public function obtenerListaSugerencias(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);
            $data = BuzonSugerenciasDirectorService::obtenerSugerencias($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function registrarRespuestaSugerencias(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);
            BuzonSugerenciasDirectorService::registrarRespuestaSugerencia($request);
            // Devuelve la fecha del servidor en formato ISO 8601
            return FormatearMensajeHelper::ok('Respuesta registrada correctamente', [
                'fecha' => Carbon::now()->toIso8601String()
            ]);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
