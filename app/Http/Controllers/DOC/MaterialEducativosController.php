<?php

namespace App\Http\Controllers\doc;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\doc\MaterialEducativoRequest;
use App\Models\doc\MaterialEducativo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MaterialEducativosController extends Controller
{
    public function list(Request $request)
    {

        try {
            Gate::authorize('tiene-perfil', [[Perfil::DOCENTE]]);
            $data = MaterialEducativo::obtenerMaterial($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
        
    }

    public function store(MaterialEducativoRequest $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DOCENTE]]);
            $data = MaterialEducativo::guardarMaterial($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }

    }

    public function update(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', [[Perfil::DOCENTE]]);
            $data = MaterialEducativo::actualizarMaterial($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function delete(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DOCENTE]]);
            $data = MaterialEducativo::eliminarMaterial($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
