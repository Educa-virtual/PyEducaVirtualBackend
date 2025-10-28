<?php

namespace App\Http\Controllers\evaluaciones;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\eval\LogroAlcanzado;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LogroAlcanzadoController extends Controller
{
    private $permitidos = [
        Perfil::DOCENTE,
    ];

    public function obtenerDatosCursoDocente(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->permitidos]);
            $data = LogroAlcanzado::selDatosCursoDocente($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function obtenerLogrosEstudiante(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->permitidos]);
            $data = LogroAlcanzado::selLogrosAlcanzadosEstudiante($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarLogro(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->permitidos]);
            $data = LogroAlcanzado::guardarLogro($request);
            return FormatearMensajeHelper::ok('Se guardó el dato', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarLogro(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->permitidos]);
            $data = LogroAlcanzado::actualizarLogro($request);
            return FormatearMensajeHelper::ok('Se actualizó el dato', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

}