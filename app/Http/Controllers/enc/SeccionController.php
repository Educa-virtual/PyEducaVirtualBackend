<?php

namespace App\Http\Controllers\enc;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\enc\InsertarCategoriaRequest;
use App\Http\Requests\enc\RegistrarCategoriaRequest;
use App\Models\enc\Seccion;
use App\Services\enc\CategoriasService;
use App\Services\enc\DocentesService;
use App\Services\enc\EncuestasService;
use App\Services\enc\EstudiantesService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SeccionController extends Controller
{
    private $encuestadores = [
        Perfil::ADMINISTRADOR_DREMO,
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::DIRECTOR_IE,
    ];

    private $encuestados = [
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::DIRECTOR_IE,
        Perfil::DOCENTE,
        Perfil::ESTUDIANTE,
        Perfil::APODERADO,
    ];

    public function listarSecciones(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->encuestadores, $this->encuestados)]);
            $data = Seccion::selSecciones($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verSeccion(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Seccion::selSeccion($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarSeccion(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Seccion::insSeccion($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarSeccion(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Seccion::updSeccion($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarSeccion(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->encuestadores]);
            $data = Seccion::delSeccion($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
