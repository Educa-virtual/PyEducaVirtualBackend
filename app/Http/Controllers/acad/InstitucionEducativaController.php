<?php

namespace App\Http\Controllers\acad;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Models\acad\InstitucionEducativa;
use App\Enums\Perfil;

class InstitucionEducativaController extends Controller
{
    public static function crearInstitucionEducativa(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO]]);
            $data = InstitucionEducativa::selInstitucionesEducativasParametros($request);
            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public static function listarInstitucionesEducativas(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO]]);
            $data = InstitucionEducativa::selInstitucionesEducativas($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public static function verInstitucionEducativa(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO]]);
            $data = InstitucionEducativa::selInstitucionEducativa($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarInstitucionEducativa(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO]]);
            $data = InstitucionEducativa::insInstitucionEducativa($request);
            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarInstitucionEducativa(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO]]);
            $data = InstitucionEducativa::updInstitucionEducativa($request);
            return FormatearMensajeHelper::ok('Se actualizó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function obtenerInstitucionesEducativas(Request $request)
    {
        try {
            $params = [
                $request->header('iCredEntPerfId'),
                $request->iUgelId,
            ];
            $placeholders = implode(',', array_fill(0, count($params), '?'));
            $insituciones = DB::select("EXEC ere.SP_SEL_instituciones $placeholders", $params);

            return FormatearMensajeHelper::ok('Datos obtenidos correctamente', $insituciones);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function obtenerIePorUgel($iUgelId) {}

    public function obtenerSedesIe($iIieeId)
    {
        try {
            $sedes = DB::select('SELECT * FROM acad.sedes WHERE iIieeId=?', [$iIieeId]);

            return FormatearMensajeHelper::ok('Datos obtenidos correctamente', $sedes);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
