<?php

namespace App\Http\Controllers\bienestar;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\bienestar\FichaDiscapacidad;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class FichaDiscapacidadController extends Controller
{
    private $registran = [
        Perfil::ESTUDIANTE,
        Perfil::APODERADO,
    ];

    public function actualizarFichaDiscapacidad(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);
            $data = FichaDiscapacidad::updFichaDiscapacidad($request);
            return FormatearMensajeHelper::ok('Se actualizó la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verFichaDiscapacidad(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);
            $data = FichaDiscapacidad::selfichaDiscapacidad($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
