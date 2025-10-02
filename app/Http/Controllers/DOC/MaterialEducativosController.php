<?php

namespace App\Http\Controllers\doc;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\doc\MaterialEducativo;
use Exception;
use Illuminate\Http\Request;
class MaterialEducativosController extends Controller
{
    public function list(Request $request)
    {

        try {
            // Gate::authorize('tiene-perfil', [[Perfil::AUXILIAR]]);
            $data = MaterialEducativo::obtenerMaterial($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
        
    }

    public function store(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', [[Perfil::AUXILIAR]]);
            $data = MaterialEducativo::guardarMaterial($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }

    }

    public function update(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', [[Perfil::AUXILIAR]]);
            $data = MaterialEducativo::actualizarMaterial($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function delete(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', [[Perfil::AUXILIAR]]);
            $data = MaterialEducativo::eliminarMaterial($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
