<?php

namespace App\Http\Controllers\enc;

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

class SeccionController extends Controller
{
    public function listarSecciones(Request $request)
    {
        try {
            $data = Seccion::selSecciones($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verSeccion(Request $request)
    {
        try {
            $data = Seccion::selSeccion($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarSeccion(Request $request)
    {
        try {
            $data = Seccion::insSeccion($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarSeccion(Request $request)
    {
        try {
            $data = Seccion::updSeccion($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarSeccion(Request $request)
    {
        try {
            $data = Seccion::delSeccion($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
