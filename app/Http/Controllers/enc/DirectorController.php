<?php

namespace App\Http\Controllers\enc;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\enc\ActualizarCategoriaRequest;
use App\Http\Requests\enc\InsertarCategoriaRequest;
use App\Http\Requests\enc\RegistrarCategoriaRequest;
use App\Services\enc\CategoriasService;
use App\Services\enc\DirectoresService;
use Exception;
use Illuminate\Http\Request;

class DirectorController extends Controller
{
    public function obtenerDirectoresParaFiltroEncuesta(Request $request)
    {
        try {
            $data = DirectoresService::obtenerDirectoresParaFiltroEncuesta($request);
            return FormatearMensajeHelper::ok('Datos obtenidos correctamente', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
