<?php

namespace App\Http\Controllers\bienestar;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\bienestar\FichaGeneral;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FichaGeneralController extends Controller
{
    private $registran = [
        Perfil::ESTUDIANTE,
        Perfil::APODERADO,
    ];

    public function verFichaGeneral(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);
            $data = FichaGeneral::selfichaGeneral($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarFichaGeneral(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);
            $data = FichaGeneral::updFichaGeneral($request);
            return FormatearMensajeHelper::ok('Se actualizo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
