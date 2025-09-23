<?php

namespace App\Http\Controllers\bienestar;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\bienestar\FichaDiscapacidadDetalle;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class FichaDiscapacidadDetalleController extends Controller
{
    private $registran = [
        Perfil::ESTUDIANTE,
        Perfil::APODERADO,
        Perfil::DOCENTE,
        Perfil::DIRECTOR_IE,
        Perfil::SUBDIRECTOR_IE,
        Perfil::ASISTENTE_SOCIAL,
    ];

    public function listarDiscapacidadesDetalle(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);
            $data = FichaDiscapacidadDetalle::selFichaDiscapacidadesDetalle($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verDiscapacidadDetalle(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);
            $data = FichaDiscapacidadDetalle::selFichaDiscapacidadDetalle($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarDiscapacidadDetalle(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);

            // Subir archivo
            if( $request->hasFile('archivo') ) {
                $archivo = $request->file('archivo');
                $iYAcadId = $request->iYAcadId;
                $iFichaDGId = $request->iFichaDGId;
                $ruta = "bienestar/ficha/$iYAcadId/$iFichaDGId";
                $request->merge([
                    'cDiscFichaArchivoNombre' => $this->subirArchivo($archivo, $ruta),
                ]);
            }

            $data = FichaDiscapacidadDetalle::insFichaDiscapacidadDetalle($request);
            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarDiscapacidadDetalle(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);

            // Subir archivo
            if( !$request->cDiscFichaArchivoNombre && $request->hasFile('archivo') ) {
                $archivo = $request->file('archivo');
                $iYAcadId = $request->iYAcadId;
                $iFichaDGId = $request->iFichaDGId;
                $ruta = "bienestar/ficha/$iYAcadId/$iFichaDGId";
                $request->merge([
                    'cDiscFichaArchivoNombre' => $this->subirArchivo($archivo, $ruta),
                ]);
            }

            $data = FichaDiscapacidadDetalle::updFichaDiscapacidadDetalle($request);
            return FormatearMensajeHelper::ok('Se actualizó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarDiscapacidadDetalle(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);
            $data = FichaDiscapacidadDetalle::borrarFichaDiscapacidadDetalle($request);
            return FormatearMensajeHelper::ok('Se borró la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    private function subirArchivo($archivo, $ruta)
    {
        // $nombre_archivo = hash('sha256', uniqid()) . '.' . $archivo->getClientOriginalExtension();
        $nombre_archivo = substr($archivo->getClientOriginalName(), 0, 150) . '.' . $archivo->getClientOriginalExtension();
        if(!Storage::disk('local')->exists($ruta)) {
            Storage::disk('local')->makeDirectory($ruta, 0755, true);
        }
        $archivo->move(Storage::disk('local')->path($ruta), $nombre_archivo);
        if (Storage::disk('local')->exists($ruta . '/' . $nombre_archivo)) {
            return $nombre_archivo;
        }
        return null;
    }

    public function descargarDiscapacidadDetalle(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->registran]);
            $data = FichaDiscapacidadDetalle::selFichaDiscapacidadDetalle($request);
            $nombre_archivo = $data->cDiscFichaArchivoNombre;
            $iYAcadId = $request->iYAcadId;
            $iFichaDGId = $data->iFichaDGId;
            $ruta = Storage::disk('local')->path("bienestar/ficha/$iYAcadId/$iFichaDGId/$nombre_archivo");
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
