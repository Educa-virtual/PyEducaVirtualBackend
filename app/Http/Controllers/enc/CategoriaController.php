<?php

namespace App\Http\Controllers\enc;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\enc\ActualizarCategoriaRequest;
use App\Http\Requests\enc\GuardarCategoriaRequest;
use App\Models\enc\Categoria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoriaController extends Controller
{
    private $encuestadores = [
        Perfil::ADMINISTRADOR_DREMO,
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::DIRECTOR_IE,
        Perfil::SUBDIRECTOR_IE,
    ];

    private $encuestados = [
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ESPECIALISTA_UGEL,
        Perfil::DIRECTOR_IE,
        Perfil::SUBDIRECTOR_IE,
        Perfil::DOCENTE,
        Perfil::ESTUDIANTE,
        Perfil::APODERADO,
    ];

    public function listarCategorias(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->encuestadores, $this->encuestados)]);
            $data = Categoria::selCategorias($request);
            foreach( $data as $key => $value) {
                if($value->cCateImagenNombre) {
                    $data[$key]->cCateImagenUrl = asset(Storage::url("encuestas/categorias/$value->cCateImagenNombre"));
                } else {
                    $data[$key]->cCateImagenUrl = asset("cursos/images/no-image.jpg");
                }
            }
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verCategoria(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->encuestadores, $this->encuestados)]);
            $data = Categoria::selCategoria($request);
            if($data->cCateImagenNombre) {
                $data->cCateImagenUrl = asset(Storage::url("encuestas/categorias/$data->cCateImagenNombre"));
            }
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarCategoria(GuardarCategoriaRequest $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO, Perfil::ESPECIALISTA_DREMO]]);

            // Subir archivo
            if( $request->hasFile('archivo') ) {
                $archivo = $request->file('archivo');
                $ruta = "encuestas/categorias";
                $request->merge([
                    'cCateImagenNombre' => $this->subirArchivo($archivo, $ruta),
                ]);
            }

            $data = Categoria::insCategoria($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarCategoria(ActualizarCategoriaRequest $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO, Perfil::ESPECIALISTA_DREMO]]);

            // Subir archivo
            if( $request->hasFile('archivo') ) {
                $archivo = $request->file('archivo');
                $ruta = "encuestas/categorias";
                $request->merge([
                    'cCateImagenNombre' => $this->subirArchivo($archivo, $ruta),
                ]);
            }

            $data = Categoria::updCategoria($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarCategoria(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO, Perfil::ESPECIALISTA_DREMO]]);
            $data = Categoria::delCategoria($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    private function subirArchivo($archivo, $ruta)
    {
        $nombre_archivo = hash('sha256', uniqid()) . '.' . $archivo->getClientOriginalExtension();
        if(!Storage::disk('public')->exists($ruta)) {
            Storage::disk('public')->makeDirectory($ruta, 0755, true);
        }
        $archivo->move(Storage::disk('public')->path($ruta), $nombre_archivo);
        if (Storage::disk('public')->exists($ruta . '/' . $nombre_archivo)) {
            return $nombre_archivo;
        }
        return null;
    }
}
