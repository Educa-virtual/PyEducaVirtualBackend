<?php

namespace App\Http\Controllers\bienestar;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\bienestar\SeguimientoBienestar;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SeguimientoBienestarController extends Controller
{
    private $perfiles_permitidos = [
        Perfil::DIRECTOR_IE,
        Perfil::SUBDIRECTOR_IE,
        Perfil::ASISTENTE_SOCIAL,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ADMINISTRADOR_DREMO,
    ];

    public function crearSeguimiento(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);
            $data = SeguimientoBienestar::selSeguimientoParametros($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verSeguimientosPersona(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);
            $data = SeguimientoBienestar::selSeguimientosPersona($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verSeguimientos(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);
            $data = SeguimientoBienestar::selSeguimientos($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarSeguimiento(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);

            // Subir archivo
            if( $request->hasFile('archivo') ) {
                $archivo = $request->file('archivo');
                $iYAcadId = $request->iYAcadId;
                $iPersId = $request->iPersId;
                $ruta = "bienestar/seguimiento/$iYAcadId/$iPersId";
                $request->merge([
                    'cSeguimArchivo' => $this->subirArchivo($archivo, $ruta),
                ]);
            }

            $data = SeguimientoBienestar::insSeguimiento($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarSeguimiento(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);
            $data = SeguimientoBienestar::updSeguimiento($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarSeguimientoArchivo(Request $request)
    {
        return FormatearMensajeHelper::ok('Desactivado');
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);

            // Subir archivo
            $archivo = $request->file('archivo');
            $ruta = 'bienestar/seguimiento';
            $request->merge([
                'cSeguimArchivo' => $this->subirArchivo($archivo, $ruta),
            ]);

            $data = SeguimientoBienestar::updSeguimientoArchivo($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verSeguimiento(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);
            $data = SeguimientoBienestar::selSeguimiento($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarSeguimiento(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);
            $data = SeguimientoBienestar::delSeguimiento($request);
            $iYAcadId = $data->iYAcadId;
            $iPersId = $data->iPersId;
            $cSeguimArchivo = $data->cSeguimArchivo;
            if ($iYAcadId && $iPersId && $cSeguimArchivo) {
                $ruta = "bienestar/seguimiento/$iYAcadId/$iPersId/$cSeguimArchivo";
                if (Storage::disk('local')->exists($ruta)) {
                    Storage::disk('local')->delete($ruta);
                }
            }
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verDatosPersona(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);
            $data = SeguimientoBienestar::selDatosPersona($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    private function subirArchivo($archivo, $ruta)
    {
        $nombre_archivo = hash('sha256', uniqid()) . '.' . $archivo->getClientOriginalExtension();
        if(!Storage::disk('local')->exists($ruta)) {
            Storage::disk('local')->makeDirectory($ruta, 0755, true);
        }
        $archivo->move(Storage::disk('local')->path($ruta), $nombre_archivo);
        if (Storage::disk('local')->exists($ruta . '/' . $nombre_archivo)) {
            return $nombre_archivo;
        }
        return null;
    }

    public function descargarSeguimiento(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->perfiles_permitidos]);
            $data = SeguimientoBienestar::selSeguimiento($request);
            $nombre_archivo = $data->cSeguimArchivo;
            $iYAcadId = $data->iYAcadId;
            $iPersId = $data->iPersId;
            $ruta = Storage::disk('local')->path("bienestar/seguimiento/$iYAcadId/$iPersId/$nombre_archivo");
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
}
