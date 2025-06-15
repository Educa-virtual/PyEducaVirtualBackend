<?php

namespace App\Http\Controllers\bienestar;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\bienestar\FichaFamiliarSaveRequest;
use App\Models\bienestar\FichaFamiliar;
use App\Services\ParseSqlErrorService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class FichaFamiliarController extends Controller
{
    private $perfiles_permitidos = [
        Perfil::ESTUDIANTE,
        Perfil::APODERADO,
        Perfil::DOCENTE,
        Perfil::DIRECTOR_IE,
    ];

    public function listarFichaFamiliares(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            $data = FichaFamiliar::selfichasFamiliaresPersonas($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarFichaFamiliar(FichaFamiliarSaveRequest $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            if( !$request->has('iPersId') || $request->iPersId == null ) {
                $data = FichaFamiliar::insPersonas($request);
            }
            $request->merge([
                'iPersId' => $data[0]->iPersId,
            ]);
            $data = FichaFamiliar::insfichaFamiliar($request);
            return FormatearMensajeHelper::ok('Se guardo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarFichaFamiliar(FichaFamiliarSaveRequest $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            if( !$request->has('iPersId') || $request->iPersId == null ) {
                $data = FichaFamiliar::insPersonas($request);
            }
            $request->merge([
                'iPersId' => $data[0]->iPersId,
            ]);
            $data = FichaFamiliar::updFichaFamiliar($request);
            return FormatearMensajeHelper::ok('Se actualizo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verFichaFamiliar(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            $data = FichaFamiliar::selFichaFamiliar($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarFichaFamiliar(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', $this->perfiles_permitidos);
            $data = FichaFamiliar::delFichaFamiliar($request);
            return FormatearMensajeHelper::ok('Se elimino la ficha familiar', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
