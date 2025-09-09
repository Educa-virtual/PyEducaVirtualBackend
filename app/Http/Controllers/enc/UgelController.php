<?php

namespace App\Http\Controllers\enc;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\enc\InsertarCategoriaRequest;
use App\Http\Requests\enc\RegistrarCategoriaRequest;
use App\Services\enc\CategoriasService;
use App\Services\enc\EncuestasService;
use App\Services\enc\EstudiantesService;
use App\Services\enc\UgelesService;
use Exception;
use Illuminate\Http\Request;

class UgelController extends Controller
{
    public function obtenerUgelesParaFiltroEncuesta(Request $request)
    {
        try {
            $data = UgelesService::obtenerUgelesParaFiltroEncuesta($request);
            return FormatearMensajeHelper::ok('Datos obtenidos correctamente', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
