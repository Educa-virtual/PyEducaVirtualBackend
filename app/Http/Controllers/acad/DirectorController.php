<?php

namespace App\Http\Controllers\acad;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Services\acad\EstudiantesService;
use App\Services\seg\UsuariosService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DirectorController extends Controller
{
    public function buscarEstudiantePorAnioSede($cPersDocumento, $iYAcadId, Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);
            $detallesUsuario = UsuariosService::obtenerDetallesCredencialEntidad($request->header('iCredEntPerfId'));
            $data = EstudiantesService::obtenerEstudiantePorIeDocumentoAnio($cPersDocumento, $detallesUsuario->iSedeId, $iYAcadId);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
