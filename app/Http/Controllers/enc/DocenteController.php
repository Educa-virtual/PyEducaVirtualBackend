<?php

namespace App\Http\Controllers\enc;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\enc\InsertarCategoriaRequest;
use App\Http\Requests\enc\RegistrarCategoriaRequest;
use App\Services\enc\CategoriasService;
use App\Services\enc\DocentesService;
use App\Services\enc\EncuestasService;
use App\Services\enc\EstudiantesService;
use Exception;
use Illuminate\Http\Request;

class DocenteController extends Controller
{
    public function obtenerDocentesParaFiltroEncuesta(Request $request)
    {
        try {
            $data = DocentesService::obtenerDocentesParaFiltroEncuesta($request);
            return FormatearMensajeHelper::ok('Datos obtenidos correctamente', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
