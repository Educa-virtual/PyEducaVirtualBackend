<?php

namespace App\Http\Controllers\acad;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\acad\Configuracion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ConfiguracionController extends Controller
{
    public function crearConfiguracion(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Configuracion::selConfiguracionParametros($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verConfiguracion(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Configuracion::selConfiguracion($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarConfiguracion(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Configuracion::insConfiguracion($request);
            return FormatearMensajeHelper::ok('Se creó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarConfiguracion(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            // Subir archivo
            if( $request->hasFile('archivo') ) {
                $archivo = $request->file('archivo');
                $iYAcadId = $request->iYAcadId;
                $ruta = "acad/configuracion/$iYAcadId";
                $request->merge([
                    'cConfigUrlRslAprobacion' => $this->subirArchivo($archivo, $ruta),
                ]);
            }
            $data = Configuracion::updConfiguracion($request);
            return FormatearMensajeHelper::ok('Se actualizó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function descargarAprobacion(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Configuracion::selConfiguracion($request);
            $nombre_archivo = $data->cConfigUrlRslAprobacion;
            $ruta = Storage::disk('local')->path("acad/configuracion");
            if (!file_exists($ruta)) {
                abort(404, 'Archivo no encontrado');
            }
            return response()->download($ruta, $nombre_archivo, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $nombre_archivo . '"',
            ]);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    private function subirArchivo($archivo, $ruta)
    {
        $nombre_archivo = hash('sha256', uniqid()) . '.' . $archivo->getClientOriginalExtension();
        if(!Storage::disk('local')->exists($ruta)) {
            Storage::disk('local')->makeDirectory($ruta);
        }
        $archivo->move(Storage::disk('local')->path($ruta), $nombre_archivo);
        if (Storage::disk('local')->exists($ruta . '/' . $nombre_archivo)) {
            return $nombre_archivo;
        }
        return null;
    }
}
