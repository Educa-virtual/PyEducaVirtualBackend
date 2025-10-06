<?php

namespace App\Http\Controllers\enc;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\enc\PlantillaSeccion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PlantillaSeccionController extends Controller
{
    private $encuestadores = [
        Perfil::ADMINISTRADOR_DREMO,
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::DIRECTOR_IE,
    ];

    public function listarPlantillaSecciones(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = PlantillaSeccion::selPlantillaSecciones($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verPlantillaSeccion(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = PlantillaSeccion::selPlantillaSeccion($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarPlantillaSeccion(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = PlantillaSeccion::insPlantillaSeccion($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarPlantillaSeccion(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = PlantillaSeccion::updPlantillaSeccion($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarPlantillaSeccion(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = PlantillaSeccion::delPlantillaSeccion($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
