<?php

namespace App\Http\Controllers\acad;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\acad\SubirArchivoRequest;
use App\Models\acad\InstitucionEducativa;
use App\Services\acad\EstudiantesService;
use App\Services\seg\UsuariosService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

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
    public function subirImagen(Request $request){
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);
            $imagen = $request->file('escudo');
            $iYAcadId = $request->iYAcadId;
            $iCredEntPerfId = $request->iCredEntPerfId;
            $data = InstitucionEducativa::subirImagen($iCredEntPerfId, $iYAcadId, $imagen);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
    public function subirDocumento(SubirArchivoRequest $request){
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);
            $data = InstitucionEducativa::subirReglamento($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
    public function descargarArchivo(Request $request){
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);
            $ruta = $request->ruta;

            if (!Storage::disk('public')->exists($ruta)) {
                throw new Exception('El archivo no existe');
            }

            $archivo = Storage::disk('public')->get($ruta);
            return $archivo;
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
