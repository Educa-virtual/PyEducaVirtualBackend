<?php

namespace App\Http\Controllers\doc;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\doc\ActividadesGestion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CargaNoLectivasController extends Controller
{
    public function list(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DOCENTE, Perfil::DIRECTOR_IE]]);
            $data = ActividadesGestion::obtenerActividades($request);

            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function store(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DOCENTE, Perfil::DIRECTOR_IE]]);
            $data = ActividadesGestion::guardarActividades($request);

            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function update(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DOCENTE]]);
            $data = ActividadesGestion::editarActividades($request);

            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function delete(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DOCENTE]]);
            $data = ActividadesGestion::eliminarActividades($request);

            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function aprobar(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);
            $data = ActividadesGestion::aprobarActividades($request);

            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function observar(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);
            $data = ActividadesGestion::observarActividades($request);

            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function descargar(Request $request)
    {
        
        $ruta = $request->ruta;
        if (! Storage::disk('public')->exists($ruta)) {
            throw new Exception('El archivo no existe');
        }

        $path = Storage::disk('public')->path($ruta);

        return response()->download($path);
    }
}
