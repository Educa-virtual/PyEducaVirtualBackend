<?php

namespace App\Http\Controllers\enc;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\enc\InsertarCategoriaRequest;
use App\Http\Requests\enc\RegistrarCategoriaRequest;
use App\Services\enc\CategoriasService;
use App\Services\enc\EncuestasService;
use Exception;
use Illuminate\Http\Request;

class EncuestaController extends Controller
{
    public function obtenerEncuestasPorCategoria($iCategoriaEncuestaId) {
        try {
            $data = EncuestasService::obtenerEncuestasPorCategoria($iCategoriaEncuestaId);
            return FormatearMensajeHelper::ok('Datos obtenidos correctamente', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
