<?php

namespace App\Http\Controllers\com;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\com\Comunicado;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ComunicadoController extends Controller
{
    private $emisores = [
        Perfil::ADMINISTRADOR_DREMO,
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::DIRECTOR_IE,
        Perfil::DOCENTE,
    ];

    private $recipientes = [
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::DIRECTOR_IE,
        Perfil::DOCENTE,
        Perfil::ESTUDIANTE,
        Perfil::APODERADO,
    ];

    public function listarComunicados(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->emisores, $this->recipientes)]);
            $data = Comunicado::selComunicados($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function crearComunicado(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [$this->emisores]);
            $data = Comunicado::selComunicadoParametros($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verComunicado(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->emisores, $this->recipientes)]);
            $data = Comunicado::selComunicado($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarComunicado(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [$this->emisores]);
            $data = Comunicado::insComunicado($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarComunicado(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [$this->emisores]);
            $data = Comunicado::delComunicado($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarComunicado(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [$this->emisores]);
            $data = Comunicado::updComunicado($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function obtenerGrupoCantidad(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->emisores)]);
            $data = Comunicado::selGrupoCantidad($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function buscarPersona(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->emisores]);
            $data = Comunicado::selBuscarPersona($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function subirDocumento(Request $request) {
        try {
            $request->merge(["nombreRuta" => "comunicados"]);
            $request->validate([
                'archivo' => 'required|file|mimes:pdf,doc,docx,png,jpeg,jpg,xlsx,pptx|max:9000',
            ]);

            $data = Comunicado::subirDocumento($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
    public function descargarDocumento(Request $request) {
        try {
            $archivo = $request->archivo;
            $ruta = Storage::disk('local')->path($archivo);
            if (!file_exists($ruta)) {
                abort(404, 'Archivo no encontrado');
            }
            return response()->download($ruta);

        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
    public function recepcionarComunicado(Request $request)
    {
        try {
           // Gate::authorize('tiene-perfil', [$this->emisores]);
            $data = Comunicado::insRecepcionarComunicado($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
