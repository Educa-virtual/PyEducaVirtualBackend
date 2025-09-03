<?php

namespace App\Http\Controllers\bienestar;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\bienestar\FichaRecreacion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FichaRecreacionController extends Controller
{
    private $registran = [
        Perfil::ESTUDIANTE,
        Perfil::APODERADO,
    ];

    public function actualizarFichaRecreacion(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);
            $data = FichaRecreacion::updFichaRecreacion($request);
            return FormatearMensajeHelper::ok('Se actualizó la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verFichaRecreacion(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);
            $data = FichaRecreacion::selFichaRecreacion($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
